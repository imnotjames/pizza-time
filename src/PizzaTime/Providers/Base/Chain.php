<?php namespace PizzaTime\Providers\Base;

use PizzaTime\Address;

interface Chain {
	/**
	 * @param Address $address
	 * @return array
	 */
	public function getStores(Address $address);

	/**
	 * @param Address $address
	 * @return null|\PizzaTime\Providers\Base\Store
	 */
	public function getDeliveryStore(Address $address);
}