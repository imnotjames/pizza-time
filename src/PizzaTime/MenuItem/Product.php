<?php namespace PizzaTime\MenuItem;

use PizzaTime\MenuItem;

class Product implements MenuItem {

	protected $configurable = true;

	protected $category;
	protected $code;
	protected $cost;
	protected $name;

	protected $currentOptions;
	protected $possibleOptions;

	/**
	 * @param string $category
	 * @param string $code
	 * @param number $cost
	 * @param string $name
	 * @param array $possibleOptions
	 * @param array $presetOptions
	 */
	public function __construct($category, $code, $cost, $name, $possibleOptions = array(), $presetOptions = array()) {
		$this->category = $category;
		$this->code = $code;
		$this->cost = $cost;
		$this->name = $name;

		$this->possibleOptions = $possibleOptions;

		foreach($presetOptions as $option) {
			$this->addConfigurationOption($option);
		}
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
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return array
	 */
	public function getConfigurationOptions() {
		return $this->possibleOptions;
	}

	/**
	 * @return array
	 */
	public function getConfiguration() {
		return $this->currentOptions;
	}

	/**
	 * @param \PizzaTime\MenuItem\Option $option
	 * @return bool
	 */
	public function addConfigurationOption(Option $option) {
		if (array_search($option, $this->possibleOptions) === FALSE) {
			return false;
		}

		$this->currentOptions[] = $option;
	}

	/**
	 * @return bool
	 */
	public function isConfigurable() {
		return $this->configurable;
	}

	/**
	 * @param \PizzaTime\MenuItem $menuItem
	 * @return bool
	 */
	public function isExclusive(MenuItem $menuItem) {
		return false;
	}
}