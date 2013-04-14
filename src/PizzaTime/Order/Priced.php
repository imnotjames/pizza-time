<?php namespace PizzaTime;

/**
 * Class Order_Priced
 * @package PizzaTime
 */
class Order_Priced extends Order {
	protected $price = 0;

	public function __construct(Customer $customer, $price) {
		parent::__construct($customer);

		$this->price = floatval($price);
	}

	/**
	 * @return number
	 */
	public function getTotal() {
		return $this->price;
	}

	/**
	 * @param Order $order
	 * @param $price
	 * @return Order_Priced
	 */
	public static function fromOrder(Order $order, $price) {
		$pricedOrder = new Order_Priced($order->getCustomer(), $price);

		$pricedOrder->setOrderID($order->getOrderID());
		$pricedOrder->setCart($order->getCart());

		return $pricedOrder;
	}
}