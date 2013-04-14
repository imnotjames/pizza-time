<?php namespace PizzaTime;

interface PaymentSource {
	/**
	 * @return string
	 */
	public function getType();

	/**
	 * @return Address
	 */
	public function getBillingAddress();

	/**
	 * @return string
	 */
	public function getValue();
}