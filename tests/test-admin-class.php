<?php

/**
 * Test admin class
 *
 * @package Wp_Expenses
 */

class Wp_Expenses_Admin_VerifyTest extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
		$this->class_instance = new Wp_Expenses_Admin('wp-expenses', '1.0.0');
	}

}
