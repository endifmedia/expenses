<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Wp_Expenses
 * @subpackage Wp_Expenses/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Expenses
 * @subpackage Wp_Expenses/admin
 * @author     Ethan Allen
 */
class Wp_Expenses_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wp_Expenses_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wp_Expenses_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        if ($this->is_expenses_posttype()) {
            wp_enqueue_style( 'jquery-timepicker-css', plugins_url( 'css/jquery.timepicker.min.css', __FILE__ ) );
            wp_enqueue_style( 'jquery-ui-datepicker', plugins_url( 'css/jquery.ui.datepicker.css', __FILE__ ) );
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wp_Expenses_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wp_Expenses_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        if ($this->is_expenses_posttype()) {
            wp_enqueue_script( 'jquery-ui-datepicker' );
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script( $this->plugin_name . '-jquery-timepicker-js', plugin_dir_url( __FILE__ ) . 'js/jquery.timepicker.min.js', array( 'jquery' ), $this->version, false );
            wp_enqueue_script( $this->plugin_name . '-admin-js', plugin_dir_url( __FILE__ ) . 'js/wp-expenses-admin.js', array( 'jquery' ), $this->version, true );
        }
    }

    /**
     * Check for our post types.
     *
     * @return bool
     */
    public function is_expenses_posttype() {

        global $post_type;

        if ($post_type === 'wp_expenses') {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Add settings menu icon and links.
     *
     * @since    1.0.0
     */
    public function add_menu_icon_button() {

        if (current_user_can('administrator')) {
            add_menu_page( 'Expenses', 'Expenses', 'edit_plugins', 'wp_expenses_settings', null, 'dashicons-analytics', 86 );
            add_submenu_page(
                'wp_expenses_settings',
                esc_html__( 'Settings', $this->plugin_name ),
                esc_html__( 'Settings', $this->plugin_name ),
                'read',
                'wp_expenses_settings',
                array( $this, 'options_page' )
            );
	        add_submenu_page(
		        'wp_expenses_settings',
		        esc_html__( 'Expense Report', $this->plugin_name ),
		        esc_html__( 'Expense Report', $this->plugin_name ),
		        'read',
		        'wp_expenses_report',
		        array( $this, 'expense_report' )
	        );
	        add_users_page(
		        esc_html__( 'My Expense Report', $this->plugin_name ),
		        esc_html__( 'My Expense Report', $this->plugin_name ),
		        'read',
		        'wp_expenses_user_report',
		        array( $this, 'user_expense_report' )
	        );

        }
    }

	/**
	 * Add post type.
	 *
	 * @since 1.0.0
	 */
    public function setup_posttypes() {

	    $set = false;

        if (current_user_can('administrator')) {
            $set = 'wp_expenses_settings';
        } elseif (current_user_can('read')) {
            $set = true;
        }

        $labels = array(
            'name'                  => _x( 'Expenses', $this->plugin_name ),
            'singular_name'         => _x( 'Expense', $this->plugin_name ),
            'menu_name'             => __( 'Expenses', $this->plugin_name ),
            'name_admin_bar'        => __( 'Expense', $this->plugin_name ),
            'all_items'             => __( 'All Expenses', $this->plugin_name ),
            'add_new_item'          => __( 'Add New Expense', $this->plugin_name ),
            'new_item'              => __( 'New Expense', $this->plugin_name ),
            'edit_item'             => __( 'Edit Expense', $this->plugin_name ),
            'update_item'           => __( 'Update Expense', $this->plugin_name ),
            'view_item'             => __( 'View Expense', $this->plugin_name ),
            'search_items'          => __( 'Search Expenses', $this->plugin_name ),
            'not_found'             => __( 'No Expenses found', $this->plugin_name ),
            'not_found_in_trash'    => __( 'No Expenses found in the Trash', $this->plugin_name ),
            'insert_into_item'      => __( 'Insert into Expense', $this->plugin_name ),
            'uploaded_to_this_item' => __( 'Uploaded to this Expense', $this->plugin_name ),
            'items_list'            => __( 'Expenses list', $this->plugin_name ),
            'items_list_navigation' => __( 'Expenses list navigation', $this->plugin_name ),
        );
        $args = array(
            'label'                 => __( 'Expense', $this->plugin_name ),
            'description'           => __( 'Expenses', $this->plugin_name ),
            'labels'                => $labels,
            'public'                => false,
            'show_ui'               => true,
            'menu_icon'             => 'dashicons-analytics',
            'capabilities'          => array(

                // meta caps (don't assign these to roles)
                'edit_post'              => 'edit_wp_expense',
                'read_post'              => 'read_wp_expense',
                'delete_post'            => 'delete_wp_expense',

                // primitive/meta caps
                'create_posts'           => 'create_wp_expenses',

                // primitive caps used outside of map_meta_cap()
                'edit_posts'             => 'edit_wp_expenses',
                'edit_others_posts'      => 'manage_wp_expenses',
                'publish_posts'          => 'publish_wp_expenses',
                'read_private_posts'     => 'read_private_wp_expenses',

                // primitive caps used inside of map_meta_cap()
                'read'                   => 'read',
                'delete_posts'           => 'delete_wp_expenses',
                'delete_private_posts'   => 'delete_private_wp_expenses',
                'delete_published_posts' => 'delete_published_wp_expenses',
                'delete_others_posts'    => 'delete_others_wp_expenses',
                'edit_private_posts'     => 'edit_private_wp_expenses',
                'edit_published_posts'   => 'edit_published_wp_expenses'
            ),
            'map_meta_cap' => true,
            'show_in_menu'          => $set,
            'can_export'            => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => false,
        );
        register_post_type('wp_expenses', $args);

    }

    /**
     * Edit post type list table.
     *
     * @since    1.0.0
     */
    public function edit_Expense_post_list($defaults) {

        unset($defaults['title']);
        unset($defaults['date']);

        $defaults['wp_expense_title'] = __('Title', $this->plugin_name);
        $defaults['wp_expense_date'] = __('Expense Date', $this->plugin_name);

        return $defaults;

    }

    /**
     * Populate invoice post type list table.
     *
     * @param $column_name
     * @param $post_ID
     */
    public function add_expense_details_to_post_list($column_name, $post_ID) {

        switch ($column_name) {

            case 'wp_expenses_date' :

                $date = get_post_meta($post_ID, 'wp_expenses_from_date', true);
                if (!empty($date)){
                    echo esc_html($date);
                }

                break;

            case 'wp_expenses_user' :

                $user = get_post_meta($post_ID, 'wp_expenses_expensee_name', true);

                if (!empty($user)) {
                    $this->get_user( $user );
                    echo '<br>user id: ' . $user;
                }

                break;

            case 'wp_expenses_total' :

                echo esc_html($this->get_expenses_total($post_ID));

                break;

            default :
                break;
        }

    }

    /**
     * @param $views
     */
    public function edit_subsubsub_links($views) {

        if (!current_user_can('administrator')) {
            unset($views['publish']);
            unset($views['all']);
            unset($views['draft']);
            unset($views['trash']);
        }
        return $views;
    }

    /**
     * Get total items for an expense card
     *
     * @param $items
     * @param bool|false $saved_from_card
     *
     * @return array
     */
    private function generate_card_totals($items, $saved_from_card = false) {

        $totals = array();

        if ($saved_from_card == false) {

            foreach ( $items as $item ) {

                switch ( $item['expense-type'] ) {
                    case 'mileage':
                        $totals['Mileage'] += $item['expense-amount'];
                        break;
                    case 'hotel':
                        $totals['Hotel'] += $item['expense-amount'];
                        break;
                    case 'plane-tickets':
                        $totals['Plane Tickets'] += $item['expense-amount'];
                        break;
                    case 'receipt':
                        $totals['Receipt'] += $item['expense-amount'];
                        break;
                    case 'food-ent':
                        $totals['Food/Ent'] += $item['expense-amount'];
                        break;
                    case 'parking':
                        $totals['Parking'] += $item['expense-amount'];
                        break;
                }

            }
        } else {
            foreach ( $items as $item ) {
                if ($item['Mileage'] ){
                    $totals['Mileage'] += $item['Mileage'];
                }
                if ($item['Hotel']) {
                    $totals['Hotel'] += $item['Hotel'];
                }
                if ($item['Plane Tickets']) {
                    $totals['Plane Tickets'] += $item['Plane Tickets'];
                }
                if ($item['Receipt']) {
                    $totals['Receipt'] += $item['Receipt'];
                }
                if ($item['Food/Ent']) {
                    $totals['Food/Ent'] += $item['Food/Ent'];
                }
                if ($item['Parking']) {
                    $totals['Parking'] += $item['Parking'];
                }
            }
        }

        return $totals;
    }

    /**
     * Get expense date range
     *
     * @param $id
     *
     * @return string
     */
    private function get_expense_card_date_range($id) {

        $fromMeta = get_post_meta($id, 'wp_expenses_from_date', true);
        $toMeta = get_post_meta($id, 'wp_expenses_to_date', true);

        $dates = 'No date set';

        if (!empty($fromMeta)) {
            $dates = date('M/j/y', strtotime($fromMeta));
        }
        if (!empty($fromMeta) && !empty($toMeta)) {
            $dates .= ' to ';
        }
        if (!empty($toMeta)) {
            $dates .= ' ' . date('M/j/y',strtotime($toMeta));
        }
        return __($dates, $this->plugin_name);
    }

    /**
     * Function that displays the options form.
     *
     * @since    1.0.0
     */
    public function options_page() {

        $options = $this->option_fields();
        $other = new Wp_Expense_Plugin_Options('Expenses', 'wp_expenses_settings', 'wp_expenses_settings');

        if (isset($_GET['tab']) && !is_numeric($_GET['tab'])){
            $active_tab = sanitize_text_field($_GET['tab']);
        } else {
            $active_tab = 'general';
        }

        $other->render_form($options, $active_tab);

    }

	/**
	 * Return report.
	 *
	 * @since 1.0.0
	 */
    public function expense_report() {
        $this->get_expenses_to_export();
    }

	/**
	 * Return report for a specific user.
	 *
	 * @since 1.0.2
	 */
	public function user_expense_report() {
		$this->get_expenses_to_export(get_current_user_id());
	}

    /**
     * Function that builds the options array for Plugin_Settings class.
     *
     * @since    1.0.0
     */
    public function option_fields() {

        $options = array(
            'general' => apply_filters('wp_expenses_general_settings',
                array(
                    'mileage_rate' => array(
                        'id'   => 'mileage_rate',
                        'label' => __('Mileage Rate:', $this->plugin_name),
                        'type' => 'text',
                        'size' => 'small',
                        'desc' => __('cents. Checkout the <a href="https://www.irs.gov/site-index-search?search=mileage+rate&field_pup_historical_1=1&field_pup_historical=1">IRS website</a> for more information.', $this->plugin_name)
                    )
                )
            )
        );
        return apply_filters('wp_expenses_settings_group', $options);

    }

    /**
     * Setup wp signups metaboxes.
     */
    public function setup_metaboxes() {
        add_meta_box( 'wp_expense_details', __( 'Expense Details', $this->plugin_name ), array(
            &$this,
            'expense_details'
        ), 'wp_expenses', 'normal' );
        if ( current_user_can( 'edit_plugins' ) ) {
            add_meta_box( 'wp_expense_user', __( 'User Details', $this->plugin_name ), array(
                &$this,
                'expense_user'
            ), 'wp_expenses', 'side' );
        }
    }

    /**
     * Save wp signups metaboxes.
     *
     * @param $post_id
     * @param $post
     */
    public function save_expenses_meta($post_id, $post) {

        if ( !current_user_can('publish_wp_expenses') ){
            return;
        }

        if (isset($_REQUEST['wp_expenses_expensee_name'])) {
            update_post_meta( $post->ID, 'wp_expenses_expensee_name', intval($_REQUEST['wp_expenses_expensee_name']) );
        }

        if (isset($_REQUEST['wp_expenses_from_date'])) {
            $year = substr($_REQUEST['wp_expenses_from_date'], -4);
            if (!empty($year)) {
                update_post_meta( $post->ID, 'wp_expenses_from_year', sanitize_text_field(intval($year)) );
            }
            update_post_meta( $post->ID, 'wp_expenses_from_date', sanitize_text_field($_REQUEST['wp_expenses_from_date']) );
        }

        if (isset($_REQUEST['wp_expenses_to_date'])) {
            update_post_meta( $post->ID, 'wp_expenses_to_date', sanitize_text_field($_REQUEST['wp_expenses_to_date']) );
        }

        if (isset($_REQUEST['wp_expenses_items'])) {

            $totals = $this->generate_card_totals($_REQUEST['wp_expenses_items']);
            if (!empty($totals)) {
                update_post_meta( $post->ID, 'wp_expenses_card_totals', $totals);
            }

            update_post_meta( $post->ID, 'wp_expenses_items', $_REQUEST['wp_expenses_items']);

        }

    }

    /**
     * Get expense report for user
     *
     * @param null $id
     */
    public function get_expenses_to_export($id = null) {

	    $args = array(
		    'posts_per_page' => -1,
		    'post_type' => 'wp_expenses',
		    'orderby'    => 'wp_expenses_from_date',
		    'order'      => 'ASC',
		    'meta_query' => array(
			    array(
				    'key'     => 'wp_expenses_from_year',
				    'value'   => date('Y'),
				    'compare' => '=',
			    )
		    ),
	    );

	    if ($id != null) {
		    $args['author'] = intval($id);
	    }

        $records = get_posts($args);

        ?>
        <div class="wrap"><!-- wp-list-table widefat fixed striped posts -->
        <h2><?php _e('Expense Report', $this->plugin_name); ?></h2>
        <table class="widefat">
            <thead>
            <tr>
                <th><?php _e('Date', $this->plugin_name); ?></th>
                <th><?php _e('Title', $this->plugin_name); ?></th>
                <th><?php _e('Hotel', $this->plugin_name); ?></th>
                <th><?php _e('Mileage', $this->plugin_name); ?></th>
                <th><?php _e('Plane Tickets', $this->plugin_name); ?></th>
                <th><?php _e('Receipt', $this->plugin_name); ?></th>
                <th><?php _e('Food/Ent', $this->plugin_name); ?></th>
                <th><?php _e('Parking', $this->plugin_name); ?></th>
                <th><?php _e('Total', $this->plugin_name); ?></th>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($records as $record) {

                $meta = get_post_meta($record->ID,'wp_expenses_card_totals', true);
                $date = $this->get_expense_card_date_range($record->ID);
                $expense_total = $this->get_expenses_total($record->ID);

                ?>
                <tr>
                    <td><?php echo $date; ?></td>
                    <td><a href="<?php echo esc_url(add_query_arg(array('post' => $record->ID,'action' => 'edit'), admin_url('post.php'))); ?>"><?php echo $record->post_title; ?></td>
                    <td><?php echo (!empty($meta['Hotel'])) ? $meta['Hotel'] : '-'; ?></td>
                    <td><?php echo (!empty($meta['Mileage'])) ? $meta['Mileage'] : '-'; ?></td>
                    <td><?php echo (!empty($meta['Plane Tickets'])) ? $meta['Plane Tickets'] : '-'; ?></td>
                    <td><?php echo (!empty($meta['Receipt']))? $meta['Receipt'] : '-'; ?></td>
                    <td><?php echo (!empty($meta['Food/Ent'])) ? $meta['Food/Ent'] : '-'; ?></td>
                    <td><?php echo (!empty($meta['Parking'])) ? $meta['Parking'] : '-'; ?></td>
                    <td><?php echo $expense_total; ?></td>
                </tr>
            <?php } ?>

            </tbody>
        </table>
        </div>
    <?php

    }

    /**
     * @param $post_id
     *
     * @return string
     */
    public function get_expenses_total($post_id) {
        $items = get_post_meta($post_id, 'wp_expenses_items', true);
        $val = 0;

        $options = get_option('wp_expenses_settings');

        if (!empty($items)) {
            $mileageRate = (!empty($options['mileage_rate'])) ? $options['mileage_rate'] : 0;
            foreach ( $items as $item ) {
                if ( $item['expense-type'] === 'mileage' ) {
                    $val = $val + $item['expense-amount'] * $mileageRate * .01;
                } else {
                    $val = $val + $item['expense-amount'];
                }
            }
        }
        //@todo edit money format
        return '$' . money_format('%i', $val);
    }

    /**
     * Get user name by user id.
     *
     * @param null $id
     */
    public function get_user($id = null) {
        if (!empty($id)) {
            $user = get_user_by('id', $id);
            echo $user->first_name . ' ' . $user->last_name;
        }
    }

    /**
     * Get user by Expense record.
     *
     * @param $post_id
     * @param string $type
     *
     * @return string
     */
    public function whose_expense($post_id, $type = 'full') {
        if (!empty($post_id)) {
            $userID = get_post_meta($post_id, 'wp_expenses_expesee_name', true);
            $user = get_user_by('id', $userID);

            switch ($type) {
                case 'first':
                    return ucwords($user->first_name);
                    break;

                case 'last':
                    return ucwords($user->last_name);

                default;
                    return ucwords($user->first_name . ' ' . $user->last_name);
            }

        }
    }

    /**
     * Edit custom post type list.
     *
     * @param $defaults
     *
     * @return mixed
     */
    public function edit_post_list($defaults) {

        //remove these..
        unset($defaults['date']);

        //add new ones
        $defaults['wp_expenses_date'] = __('Date', $this->plugin_name);
        $defaults['wp_expenses_user'] = __('User', $this->plugin_name);
        $defaults['wp_expenses_total'] = __('Expense Total', $this->plugin_name);
        return $defaults;
    }

    /**
     * Expense metabox.
     *
     * @since 1.0.0
     */
    public function expense_details() {

        global $post;

        $meta = get_metadata('post', $post->ID, '', true);

        if (!empty($meta)) {
            $line_items = maybe_unserialize( $meta['wp_expenses_items'][0] );
        }

        ?>
        <table class="form-table">
            <tbody>
            <tr class="">
                <th><label for="expensee-name"><?php _e('Your Name', $this->plugin_name); ?></label></th>

                <?php if (!current_user_can('administrator')) {
                    $user = (!empty($meta['wp_expenses_expensee_name'][0])) ? $meta['wp_expenses_expensee_name'][0] : wp_get_current_user();
                ?>
                <td>
                <input type="hidden" name="wp_expenses_expensee_name" id="expensee-name" value="<?php echo (!empty($meta['wp_expenses_expesee_name'][0])) ? esc_attr($meta['wp_expenses_expesee_name'][0]) : esc_attr($user->ID); ?>">
                <input type="text" name="" id="" value="<?php echo esc_attr($user->first_name . ' ' . $user->last_name); ?>" class="ltr" disabled>

                <?php } else { ?>
                <?php

                    $users = get_users();
                    echo '<td>';
                    echo '<select name="wp_expenses_expensee_name" id="expensee-name" class="expensee-name">';
                    echo '<option>--</option>';

                    foreach ($users as $user) {

                        $expensee = (!empty($meta['wp_expenses_expensee_name'][0])) ?  $meta['wp_expenses_expensee_name'][0] : '';

                        $lname = (!empty($lname = get_user_meta($user->ID, 'last_name', true))) ? $lname : '';
                        $fname = (!empty($fname = get_user_meta($user->ID, 'first_name', true))) ? $fname : '';

                        echo '<option value="' . $user->ID . '"' . selected($expensee, $user->ID ) . '>' . $fname . ' ' . $lname . ' (user id: ' . $user->ID . ')</option>';
                    }

                    echo '</select>';
                    echo '</td>';

                ?>
            <?php } ?>
            <td><span><?php _e('Total Expense', $this->plugin_name); ?></span> <span id="total-expense-cost" class="ltr"><?php if ($post) echo $this->get_expenses_total($post->ID); ?></span>

            </tr>
            <tr class="">
                <th><label for="expense-from-date"><?php _e('From Date', $this->plugin_name); ?></label></th>
                <td><input type="text" name="wp_expenses_from_date" id="expense-from-date" value="<?php echo (!empty($meta['wp_expenses_from_date'][0])) ? esc_attr($meta['wp_expenses_from_date'][0]) : ''; ?>" class="ltr wp-expenses-datepicker">
                </td>
            </tr>
            <tr class="">
                <th><label for="expensee-to-date"><?php _e('To Date', $this->plugin_name); ?></label></th>
                <td><input type="text" name="wp_expenses_to_date" id="wp_expenses_to_date" value="<?php echo (!empty($meta['wp_expenses_to_date'][0])) ? esc_attr($meta['wp_expenses_to_date'][0]) : ''; ?>" class="ltr wp-expenses-datepicker">
                </td>
            </tr>
            </tbody>
        </table>


        <table class="widefat">
            <thead>
            <tr>
                <th width="16"></th>
                <th width="20"><?php _e('Expense Type', $this->plugin_name); ?></th>
                <th width="40" align="right"><?php _e('Amount', $this->plugin_name); ?></th>
                <th width="10"></th>
            </tr>
            </thead>

            <?php
            if (!empty($line_items)) {
                $it = 0;
                foreach ( $line_items as $line_item ) {
                    echo '<tr id="clonedInput-' . $it . '" class="clonedInput">';
                    echo '<td><span class="dashicons dashicons-minus remove-expense"></span> <span class="dashicons dashicons-plus add-expense"></span></td>';
                    echo '<td>';
                    echo '<select name="wp_expenses_items[' . $it . '][expense-type]" id="expense-type" class="expense-type">';
                    echo '<option>--</option>';
                    echo '<option value="mileage"' . selected( $line_item['expense-type'], 'mileage' ) . '>' . __('Mileage', $this->plugin_name) . '</option>';
                    echo '<option value="hotel"' . selected( $line_item['expense-type'], 'hotel' ) . '>' . __('Hotel', $this->plugin_name) . '</option>';
                    echo '<option value="plane-tickets"' . selected( $line_item['expense-type'], 'plane-tickets' ) . '>' . __('Plane Tickets', $this->plugin_name) .'</option>';
                    echo '<option value="receipt"' . selected( $line_item['expense-type'], 'receipt' ) . '>' . __('Receipt', $this->plugin_name) .'</option>';
                    echo '<option value="food-ent"' . selected( $line_item['expense-type'], 'food-ent' ) . '>' .  __('Food/Ent', $this->plugin_name) .'</option>';
                    echo '<option value="parking"' . selected( $line_item['expense-type'], 'parking' ) . '>' . __('Parking', $this->plugin_name) . '</option>';
                    echo '</select>';
                    echo '</td>';

                    echo '<td><input type="text" name="wp_expenses_items[' . $it . '][expense-amount]" value="' . $line_item['expense-amount'] . '" class="ltr" /></td>';

                    $style = ('mileage' != $line_item['expense-type']) ? 'style="display:none"' : '';
                    $mileageCost = ('mileage' == $line_item['expense-type']) ? $line_item['expense-amount'] * get_option('wp_expenses_settings')['mileage_rate'] *.01 : '' ;
                    echo '<td><span class="mileage-reimbursement-count"' . $style . '><span class="currency-type">$</span> <span class="mileage-reimbursement-total">' . money_format('%i', $mileageCost) . '</span></span></td>';

                    echo '</tr>';
                    $it ++;
                }
            } else {
                echo '<tr id="clonedInput-0" class="clonedInput">';
                echo '<td><span class="dashicons dashicons-minus"></span> <span class="dashicons dashicons-plus add-expense"></span></td>';
                echo '<td>';
                echo '<select name="wp_expenses_items[0][expense-type]" id="expense-type" class="expense-type">';
                echo '<option>--</option>';
                echo '<option value="mileage">' .  __('Mileage', $this->plugin_name) . '</option>';
                echo '<option value="hotel">' . __('Hotel', $this->plugin_name) . '</option>';
                echo '<option value="plane-tickets">' . __('Plane Tickets', $this->plugin_name) .'</option>';
                echo '<option value="receipt">' . __('Receipt', $this->plugin_name) .'</option>';
                echo '<option value="food-ent">' . __('Food/Ent', $this->plugin_name) . '</option>';
                echo '<option value="parking">' . __('Parking', $this->plugin_name) . '</option>';
                echo '</select>';
                echo '</td>';
                echo '<td><input type="text" name="wp_expenses_items[0][expense-amount]" value="" class="ltr" /></td>';
                echo '<td><span class="mileage-reimbursement-count" style="display:none"><span class="currency-type">$</span> <span class="mileage-reimbursement-total">--</span></span></td>';
                echo '</tr>';
            }
            ?>
        </table>
    <?php }

    /**
     * User metabox
     */
    public function expense_user() {

        global $post;

        $meta = get_metadata('post', $post->ID, '', true);

        ?>
        <div class="wp-expense-user-details-container">
            <?php if (!empty($meta['wp_expenses_expensee_name'][0])) { ?>
                <?php $user = get_userdata($meta['wp_expenses_expensee_name'][0]); ?>

                <?php if (!empty($user)) { ?>
                    <?php echo get_avatar($user->ID); ?>
                    <ul>
                        <li><?php _e('User ID: ', $this->plugin_name); ?><?php echo $user->ID; ?></li>
                        <li><?php _e('Email: ', $this->plugin_name); ?> <?php echo $user->user_email; ?></li>
                        <li><?php _e('Login: ', $this->plugin_name); ?> <?php echo $user->user_login; ?></li>
                    </ul>
                <?php } else { ?>
                    <ul>
                        <li><?php _e('No user info at the moment.', $this->plugin_name); ?></li>
                    </ul>
                <?php } ?>

            <?php } else { ?>
                <ul>
                    <li><?php _e('No user info at the moment.', $this->plugin_name); ?></li>
                </ul>
            <?php } ?>

        </div>
    <?php }

}
