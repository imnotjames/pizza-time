<?php namespace PizzaTime;

use PizzaTime\Customer;
use PizzaTime\MenuItem;

class Order implements \Iterator {
	/**
	 * @var Customer
	 */
	protected $customer;

	/**
	 * @var array
	 */
	protected $cart = array();

	/**
	 * @var array
	 */
	protected $paymentSources = array();

	/**
	 * @var string|null
	 */
	protected $orderID = NULL;

	public function __construct(Customer $customer) {
		$this->customer = $customer;
	}

	/**
	 * @return number
	 */
	public function getTotal() {
		return array_sum(
			array_map(
				function($menuItem) {
					if ($menuItem instanceof MenuItem) {
						return $menuItem->getCost();
					}

					return 0;
				},
			$this->cart
			)
		);
	}

	/**
	 * @return Customer
	 */
	public function getCustomer() {
		return $this->customer;
	}

	/**
	 * @param array $cart
	 */
	public function setCart(array $cart) {
		$this->cart = $cart;
	}

	/**
	 * @return array
	 */
	public function getCart() {
		return $this->cart;
	}

	/**
	 * @return null|string
	 */
	public function getOrderID() {
		return $this->orderID;
	}

	/**
	 * @param $orderID
	 */
	public function setOrderID($orderID) {
		$this->orderID = '' . $orderID;

	}

	/**
	 * @param MenuItem $menuItem
	 */
	public function addMenuItem(MenuItem $menuItem) {
		$this->cart[] = $menuItem;
	}

	/**
	 * @param PaymentSource $source
	 */
	public function addPaymentSource(PaymentSource $source) {
		$this->paymentSources[] = $source;
	}

	/**
	 * @return array
	 */
	public function getPaymentSources() {
		return $this->paymentSources;
	}

	/**
	 * @return mixed
	 */
	public function current() {
		return current($this->cart);
	}

	/**
	 * @return mixed|void
	 */
	public function next() {
		return next($this->cart);
	}

	/**
	 * @return mixed
	 */
	public function key() {
		return key($this->cart);
	}

	/**
	 * @return bool
	 */
	public function valid() {
		return current($this->cart) === FALSE;
	}

	/**
	 * @return mixed|void
	 */
	public function rewind() {
		return reset($this->cart);
	}
}