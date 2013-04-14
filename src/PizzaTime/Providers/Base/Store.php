<?php namespace PizzaTime\Providers\Base;

use PizzaTime\Order;

interface Store {
	/**
	 * @return string
	 */
	public function getStoreID();

	/**
	 * @return bool
	 */
	public function isDelivering();

	/**
	 * @return number
	 */
	public function getDistance();

	/**
	 * @return array
	 */
	public function getMenuItems();

	/**
	 * @param \PizzaTime\Order $order
	 * @return \PizzaTime\Order_Priced
	 */
	public function price(Order $order);

	/**
	 * @param \PizzaTime\Order $order
	 * @return \PizzaTime\Order_Completed
	 */
	public function order(Order $order);
}
