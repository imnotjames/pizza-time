<?php namespace PizzaTime\Providers\Dominos;

use PizzaTime\Address;
use PizzaTime\Providers\Base\Chain as BaseChain;

class Chain implements BaseChain {
	const POWER_HOST = 'order.dominos.com';
	const POWER_URI = 'https://%s/power/';

	const LOCATOR_COMMAND = 'store-locator';
	const VALIDATE_ORDER_COMMAND = 'validate-order';
	const PRICE_ORDER_COMMAND = 'price-order';
	const PLACE_ORDER_COMMAND = 'place-order';
	const MENU_COMMAND = 'store/%d/menu';

	/**
	 * @param $command
	 * @param null $query
	 * @param null $post
	 * @return mixed
	 */
	public static function power($command, $query = NULL, $post = NULL) {
		$uri = sprintf(self::POWER_URI, self::POWER_HOST) . $command;

		if (!empty($query)) {
			$uri .= '?' . http_build_query($query);
		}

		if (!empty($post)) {
			$opts = array(
				'http' => array(
					'method' => 'POST',
					'header' => 'Content-Type: application/json',
					'content' => str_replace('\\/', '/', json_encode($post)),
				)
			);
		} else {
			$opts = array(
				'http' => array(
					'method' => 'GET'
				)
			);
		}

		$context = stream_context_create($opts);

		return json_decode(file_get_contents($uri, false, $context));
	}

	/**
	 * @param Address $address
	 * @return array
	 */
	public function getStores(Address $address) {
		$storeData = self::power(self::LOCATOR_COMMAND, array(
				'type' => 'Delivery',
				'c' => $address->getCity() . ', ' . $address->getRegion(),
				's' => $address->getStreet()
			)
		);

		$stores = array_map(
			function($s) {
				return new Store($s->StoreID, $s->MinDistance, $s->ServiceIsOpen->Delivery == 1 && $s->IsDeliveryStore == 1);
			},
			$storeData->{'Stores'}
		);

		return $stores;
	}

	/**
	 * @param Address $address
	 * @return \PizzaTime\Providers\Base\Store|null
	 */
	public function getDeliveryStore(Address $address) {
		$stores = self::getStores($address);

		foreach($stores as $store) {
			if ($store->isDelivering()) {
				return $store;
			}
		}

		return NULL;
	}
}
