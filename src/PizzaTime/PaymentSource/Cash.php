<?php namespace PizzaTime\PaymentSource;

use PizzaTime\PaymentSource;

/**
 * Class Cash
 * @package PizzaTime\PaymentSource
 */
class Cash implements PaymentSource {
	/**
	 *
	 */
	public function __construct() {}

	/**
	 * @return string
	 */
	public function getType() {
		return 'CASH';
	}

	/**
	 * @return null|\PizzaTime\Address
	 */
	public function getBillingAddress() {
		return NULL;
	}

	/**
	 * @return string
	 */
	public function getValue() {
		return 'CASH';
	}
}