<?php namespace PizzaTime;

/**
 * Class Order_Completed
 * @package PizzaTime
 */
class Order_Completed extends Order {
	/**
	 * @param Order $order
	 * @return Order_Completed
	 */
	public static function fromOrder(Order $order) {
		$completedOrder = new Order_Completed($order->getCustomer());

		$completedOrder->setOrderID($order->getOrderID());
		$completedOrder->setCart($order->getCart());

		return $completedOrder;
	}
}