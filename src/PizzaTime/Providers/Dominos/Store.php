<?php namespace PizzaTime\Providers\Dominos;

use PizzaTime\Address;
use PizzaTime\Order;
use PizzaTime\Order_Completed;
use PizzaTime\Order_Priced;
use PizzaTime\MenuItem\Product;
use PizzaTime\MenuItem\SizeOption;
use PizzaTime\MenuItem\ToppingOption;
use PizzaTime\PaymentSource\Credit;
use PizzaTime\Providers\Base\Store as BaseStore;

use PizzaTime\InvalidOrderException;
use PizzaTime\StoreClosedException;

class Store implements BaseStore {

	protected $storeID;
	protected $distance;
	protected $can_deliver;

	/**
	 * @param $storeID
	 * @param $distance
	 * @param $can_deliver
	 */
	public function __construct($storeID, $distance, $can_deliver) {
		$this->storeID = $storeID;
		$this->distance = $distance;
		$this->can_deliver = $can_deliver == true;
	}

	/**
	 * @return string
	 */
	public function getStoreID() {
		return $this->storeID;
	}

	/**
	 * @return bool
	 */
	public function isDelivering() {
		return $this->can_deliver;
	}

	/**
	 * @return number
	 */
	public function getDistance() {
		return $this->distance;
	}

	/**
	 * @return array
	 */
	public function getMenuItems() {
		$menuData = Chain::power(sprintf(Chain::MENU_COMMAND, $this->storeID));

		$options = array();
		$sizes = array();

		foreach($menuData->{'Options'}->{'Data'} as $row) {
			$row = array_combine($menuData->{'Options'}->{'Columns'}, $row);

			$options[$row['CategoryCode']][] = new ToppingOption($row['Name'], $row['Code'], 0, $row['CategoryCode']);
		}

		foreach($menuData->{'Sizes'}->{'Data'} as $row) {
			$row = array_combine($menuData->{'Sizes'}->{'Columns'}, $row);

			$sizes[$row['CategoryCode']][] = new SizeOption($row['Name'], $row['Code'], 0, $row['CategoryCode']);
		}

		$products = array();

		foreach($menuData->{'Products'}->{'Data'} as $row) {
			$row = array_combine($menuData->{'Products'}->{'Columns'}, $row);

			$possibleOptions = array();
			$presetOptions = array();

			$row['AllowedOptions'] = array_filter(explode(',', $row['AllowedOptions']));
			$row['DefaultOptions'] = array_filter(explode(',', $row['DefaultOptions']));

			foreach($row['AllowedOptions'] as $allowedOption) {
				$allowedOption = preg_replace('/=.+$/', '', $allowedOption);

				foreach($options[$row['CategoryCode']] as $option) {
					if ($option->getCode() == $allowedOption) {
						$possibleOptions[] = $option;
						break;
					}
				}
			}

			if (!empty($row['SizeCode'])) {
				foreach($sizes[$row['CategoryCode']] as $sizeOption) {
					if ($sizeOption->getCode() == $row['SizeCode']) {
						// Make sure it's a default option
						$presetOptions[] = $sizeOption;

						// Also make sure it's the only possible size option
						//  since that's how dominos products work
						$possibleOptions[] = $sizeOption;

						break;
					}
				}
			}

			foreach($row['DefaultOptions'] as $defaultOption) {
				$defaultOption = preg_replace('/=.+$/', '', $defaultOption);

				foreach($possibleOptions as $possibleOption) {
					if ($possibleOption->getCode() == $defaultOption) {
						$presetOptions[] = $possibleOption;
						break;
					}
				}
			}

			$products[] = new Product($row['CategoryCode'], $row['Code'], $row['Price'], $row['Name'], $possibleOptions, $presetOptions);
		}

		return $products;
	}

	/**
	 * @param \PizzaTime\Order $order
	 * @return \PizzaTime\Order_Priced
	 */
	public function price(Order $order) {
		$orderArray = $this->getOrderArray($this, $order);

		$validatedResult = Chain::power(Chain::VALIDATE_ORDER_COMMAND, NULL, $orderArray);

		$order->setOrderID($validatedResult->{'Order'}->{'OrderID'});

		$orderArray = $this->getOrderArray($this, $order);

		$pricingResult = Chain::power(Chain::PRICE_ORDER_COMMAND, NULL, $orderArray);

		return Order_Priced::fromOrder($order, floatval($pricingResult->{'Order'}->{'Amounts'}->{'Payment'}));
	}

	/**
	 * @param Order $order
	 * @throws \PizzaTime\StoreClosedException
	 * @throws \PizzaTime\InvalidOrderException
	 * @return Order_Completed
	 */
	public function order(Order $order) {
		if ($order instanceof Order_Priced) {
			$orderID = $order->getOrderID();

			if (empty($orderID)) {
				$orderArray = $this->getOrderArray($this, $order);

				$validatedResult = Dominos::power(Dominos::VALIDATE_ORDER_COMMAND, NULL, $orderArray);

				$order->setOrderID($validatedResult->{'Order'}->{'OrderID'});
			}
		} else {
			$order = $this->price($order);
		}

		$orderArray = $this->getOrderArray($this, $order, true);

		$orderResult = Chain::power(Chain::PLACE_ORDER_COMMAND, NULL, $orderArray);

		if ($orderResult->{'Status'} != 0) {
			throw new InvalidOrderException('Unknown error with placing order.');
		}

		return Order_Completed::fromOrder($order);
	}

	/**
	 * @param Address $address
	 * @return array
	 */
	private static function getAddressArray(Address $address) {
		$type = $address->getType();

		return array(
			'Street' => $address->getStreet(),
			'City' => $address->getCity(),
			'Region' => $address->getRegion(),
			'PostalCode' => $address->getPostalCode(),
			'Type' => ucwords($type),
			'OrganizationName' => $address->getOrganizationName(),
			'UnitNumber' => $address->getUnit(),
		);
	}

	/**
	 * @param array $cart
	 * @return array
	 */
	private static function getProductArray(array $cart) {
		$productArray = array();

		$id = 1;

		foreach($cart as $menuItem) {

			$menuItemArray = array(
				'Code' => $menuItem->getCode(),
				'Qty' => 1,
				'ID' => $id++,
				'isNew' => true,
				'Options' => array()
			);

			$options = $menuItem->getConfiguration();

			foreach($options as $option) {

				if ($option instanceof ToppingOption) {
					$menuItemArray['Options'][$option->getCode()] = array('1/1' => '1');
				}
			}

			$productArray[] = $menuItemArray;
		}
		return $productArray;
	}

	/**
	 * @param array $paymentSources
	 * @param $amount
	 * @return array
	 */
	private static function getPaymentArray($paymentSources = array(), $amount) {
		if (empty($paymentSources)) {
			return array();
		}

		$amountPerSource = $amount / count($paymentSources);

		$paymentArray = array();

		foreach($paymentSources as $paymentSource) {
			$paymentSourceArray = array(
				'Type' => $paymentSource->getType(),
				'Amount' => $amountPerSource,
			);

			if ($paymentSource instanceof Credit) {
				$paymentSourceArray['Number'] = $paymentSource->getValue();
				$paymentSourceArray['CardType'] = strtotupper($paymentSource->getCardType());
				$paymentSourceArray['Expiration'] = $paymentSource->getExpiration()->format('my');
				$paymentSourceArray['SecurityCode'] = $paymentSource->getCVC();
				$paymentSourceArray['PostalCode'] = $paymentSource->getBillingAddress()->getPostalCode();
			}

			$paymentArray[] = $paymentSourceArray;
		}

		return $paymentArray;
	}

	/**
	 * @param Store $store
	 * @param Order $order
	 * @param bool $includePayment
	 * @return array
	 */
	private static function getOrderArray(Store $store, Order $order, $includePayment = false) {
		$customer = $order->getCustomer();

		return array('Order' =>
			array(
				'Address' => self::getAddressArray($customer->getAddress()),

				'Coupons' => array(

				),

				'CustomerID' => '',
				'Email' => $customer->getEmailAddress(),
				'Extension' => '',
				'FirstName' => $customer->getFirstName(),
				'LastName' => $customer->getLastName(),

				'LanguageCode' => 'en',

				'OrderChannel' => 'OLO',
				'OrderID' => $order->getOrderID(),

				'OrderMethod' => 'Web',
				'OrderTaker' => null,

				'Payments' => $includePayment ? self::getPaymentArray($order->getPaymentSources(), $order->getTotal()) : array(),

				'Phone' => $customer->getPhoneNumber(),

				'Products' => self::getProductArray($order->getCart()),

				'ServiceMethod' => 'Delivery',

				'SourceOrganizationURI' => Chain::POWER_HOST,

				'StoreID' => $store->getStoreID(),

				'Tags' => (object) NULL,
				'Version' => '1.0',
				'NoCombine' => true,
				'Partners' => (object) NULL
			)
		);
	}
}