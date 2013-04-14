<?php namespace PizzaTime\MenuItem;

use PizzaTime\MenuItem;

/**
 * Class Option
 * @package PizzaTime\MenuItem
 */
abstract class Option implements MenuItem {
	protected $name;
	protected $code;
	protected $cost;
	protected $category;

	/**
	 * @param string $name
	 * @param string $code
	 * @param number|int $cost
	 * @param null|string $category
	 */
	public function __construct($name, $code, $cost = 0, $category = NULL) {
		$this->name = $name;
		$this->code = $code;
		$this->cost = $cost;
		$this->category = $category;
	}

	/**
	 * @param MenuItem $item
	 * @return bool
	 */
	public function isExclusive(MenuItem $item) {
		if ($item->getCode() == $this->getCode()) {
			return true;
		}

		return false;
	}

	/**
	 * @return string
	 */
	public function getCategory() {
		return $this->category;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * @return float
	 */
	public function getCost() {
		return $this->cost;
	}

	/**
	 * @return bool
	 */
	public function isConfigurable() {
		return false;
	}

	/**
	 * @return array
	 */
	public function getConfigurationOptions() {
		return array();
	}

	/**
	 * @return array
	 */
	public function getConfiguration() {
		return array();
	}

	/**
	 * @param \PizzaTime\MenuItem\Option $option
	 * @return bool
	 */
	public function addConfigurationOption(Option $option) {
		return false;
	}
}