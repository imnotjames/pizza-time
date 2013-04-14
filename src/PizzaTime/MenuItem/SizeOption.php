<?php namespace PizzaTime\MenuItem;

use PizzaTime\MenuItem;

/**
 * Class SizeOption
 * @package PizzaTime
 */
class SizeOption extends Option {
	/**
	 * @param MenuItem $menuItem
	 * @return bool
	 */
	public function isExclusive(MenuItem $menuItem) {
		return $menuItem instanceof self;
	}
}