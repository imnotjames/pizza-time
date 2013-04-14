<?php namespace PizzaTime;

use PizzaTime\MenuItem\Option;

interface MenuItem {
	/**
	 * @return string
	 */
	public function getCategory();

	/**
	 * @return string
	 */
	public function getCode();

	/**
	 * @return float
	 */
	public function getCost();

	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @return array
	 */
	public function getConfigurationOptions();

	/**
	 * @return array
	 */
	public function getConfiguration();

	/**
	 * @param \PizzaTime\MenuItem\Option $option
	 * @return bool
	 */
	public function addConfigurationOption(Option $option);

	/**
	 * @return bool
	 */
	public function isConfigurable();

	/**
	 * @param \PizzaTime\MenuItem $menuItem
	 * @return bool
	 */
	public function isExclusive(MenuItem $menuItem);
}