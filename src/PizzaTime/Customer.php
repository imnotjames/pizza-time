<?php namespace PizzaTime;

/**
 * Class Customer
 * @package PizzaTime
 */
class Customer {
	protected $address;
	protected $firstName;
	protected $lastName;

	protected $phoneNumber = '';
	protected $emailAddress = '';

	/**
	 * @param $firstName
	 * @param $lastName
	 * @param $phoneNumber
	 * @param Address $address
	 */
	public function __construct($firstName, $lastName, $phoneNumber, Address $address) {
		$this->address = $address;

		$this->phoneNumber = $phoneNumber;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
	}

	/**
	 * @return Address
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * @return string
	 */
	public function getFirstName() {
		return $this->firstName;
	}

	/**
	 * @return string
	 */
	public function getLastName() {
		return $this->lastName;
	}

	/**
	 * @return string
	 */
	public function getPhoneNumber() {
		return $this->phoneNumber;
	}

	/**
	 * @param $emailAddress
	 */
	public function setEmailAddress($emailAddress) {
		$this->emailAddress = $emailAddress;
	}

	/**
	 * @return string
	 */
	public function getEmailAddress() {
		return $this->emailAddress;
	}
}