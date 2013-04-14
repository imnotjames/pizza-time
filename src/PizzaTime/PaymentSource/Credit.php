<?php namespace PizzaTime\PaymentSource;

use PizzaTime\PaymentSource;
use PizzaTime\Address;

/**
 * Class Credit
 * @package PizzaTime\PaymentSource
 */
class Credit implements PaymentSource {
	private $billingAddress;

	private $cardType;
	private $cardNumber;
	private $cvc;

	private $expiration;

	/**
	 * @param Address $billingAddress
	 * @param string $cardType
	 * @param string $cardNumber
	 * @param string $cvc
	 * @param \DateTime $expiration
	 */
	public function __construct(Address $billingAddress, $cardType, $cardNumber, $cvc, \DateTime $expiration) {
		$this->billingAddress = $billingAddress;

		$this->cardType = $cardType;
		$this->cardNumber = $cardNumber;
		$this->cvc = $cvc;

		$this->expiration = $expiration;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return 'CREDIT';
	}

	/**
	 * @return Address
	 */
	public function getBillingAddress() {
		return $this->billingAddress;
	}

	/**
	 * @return string
	 */
	public function getValue() {
		return $this->cardNumber;
	}

	/**
	 * @return string
	 */
	public function getCardType() {
		return $this->cardType;
	}

	/**
	 * @return string
	 */
	public function getCVC() {
		return $this->cvc;
	}

	/**
	 * @return \DateTime
	 */
	public function getExpiration() {
		return $this->expiration;
	}
}