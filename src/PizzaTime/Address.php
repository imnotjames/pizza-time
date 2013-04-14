<?php namespace PizzaTime;

class Address {
	protected $street;
	protected $city;
	protected $region;
	protected $postalCode;

	protected $unit;

	protected $type;
	protected $organizationName;

	/**
	 * @param $street
	 * @param $city
	 * @param $region
	 * @param $postalCode
	 * @param null $unit
	 * @param string $type
	 * @param null $organizationName
	 */
	public function __construct($street, $city, $region, $postalCode, $unit = NULL, $type = 'House', $organizationName = NULL) {
		$this->street = $street;
		$this->city = $city;
		$this->region = $region;
		$this->postalCode = $postalCode;

		$this->unit = $unit;

		$this->type = $type;
		$this->organizationName = $organizationName;
	}

	/**
	 * @return string
	 */
	public function getStreet() {
		return $this->street;
	}

	/**
	 * @return string
	 */
	public function getCity() {
		return $this->city;
	}

	/**
	 * @return string
	 */
	public function getRegion() {
		return $this->region;
	}

	/**
	 * @return string
	 */
	public function getPostalCode() {
		return $this->postalCode;
	}

	/**
	 * @return string
	 */
	public function getUnit() {
		return $this->unit;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function getOrganizationName() {
		return $this->organizationName;
	}
}