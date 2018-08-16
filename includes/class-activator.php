<?php

/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 *
 * @package    Wp_Expenses
 * @subpackage Wp_Expenses/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Expenses
 * @subpackage Wp_Expenses/includes
 * @author     Ethan Allen
 */
class Wp_Expenses_Activator {

    /**
     * Constructor function
     *
     * @since    1.0.0
     */
    public static function activate() {
		self::add_role_caps();
	    self::init_settings();
    }

	/**
	 * Add roles.
	 *
	 * @since    1.0.0
	 */
	public function add_role_caps() {

		$roles = array('subscriber', 'contributor', 'author', 'editor', 'administrator');

		foreach ($roles as $role) {

			$currRole = get_role($role);

			if ('administrator' == $role) {
				$currRole->add_cap( 'delete_wp_expenses' );
				$currRole->add_cap( 'delete_private_wp_expenses' );
				$currRole->add_cap( 'delete_published_wp_expenses' );
				$currRole->add_cap( 'delete_others_wp_expenses' );
				$currRole->add_cap( 'edit_other_wp_expenses' );
				$currRole->add_cap( 'manage_wp_expenses' );
				$currRole->add_cap( 'read_private_wp_expenses' );
			}

			$currRole->add_cap( 'create_wp_expenses' );
			$currRole->add_cap( 'publish_wp_expenses' );

		}

	}

	/**
	 * Init settings.
	 *
	 * @since    1.0.0
	 */
	public function init_settings() {
		$options = get_option('wp_expenses_settings');

		if (empty($options)) { // set option defaults
            $options['mileage_rate'] = '';
			update_option('wp_expenses_settings', $options);
		}
	}

}
