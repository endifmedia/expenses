<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    Wp_Expenses
 * @subpackage Wp_Expenses/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wp_Expenses
 * @subpackage Wp_Expenses/includes
 * @author     Ethan Allen
 */
class Wp_Expenses {

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {

        $this->plugin_name = 'wp-expenses';
        $this->version = '1.0.1';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Wp_Expenses_Loader. Orchestrates the hooks of the plugin.
     * - Wp_Expenses_i18n. Defines internationalization functionality.
     * - Wp_Expenses_Admin. Defines all hooks for the admin area.
     * - Wp_Expenses_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin.php';

        /**
         * The class responsible for defining and saving plugin settings.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/class-plugin-options.php';

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Wp_Expenses_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new Wp_Expenses_i18n();

        add_action( 'plugins_loaded', array($plugin_i18n, 'load_plugin_textdomain') );

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        $plugin_admin = new Wp_Expenses_Admin( $this->get_plugin_name(), $this->get_version() );

        add_action( 'admin_enqueue_scripts', array($plugin_admin, 'enqueue_styles') );
        add_action( 'admin_enqueue_scripts', array($plugin_admin, 'enqueue_scripts') );
        add_action( 'admin_menu', array($plugin_admin, 'add_menu_icon_button') );
        add_action( 'init', array($plugin_admin, 'setup_posttypes') );

        add_action( 'add_meta_boxes', array($plugin_admin, 'setup_metaboxes'), 10, 2);
        add_action( 'post_updated', array($plugin_admin, 'save_expenses_meta'), 10, 2);
        add_action( 'save_post_wp_expenses', array($plugin_admin, 'send_notifications'), 10, 3);

        add_action( 'manage_wp_expenses_posts_columns', array($plugin_admin, 'edit_post_list'), 10, 2);
        add_action( 'manage_wp_expenses_posts_custom_column', array($plugin_admin, 'add_expense_details_to_post_list'), 10, 2 );
        add_action( 'views_edit-wp_expenses', array($plugin_admin, 'edit_subsubsub_links') );

    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

}
