<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       wisener.me
 * @since      1.0.0
 *
 * @package    Autotune_Remaps
 * @subpackage Autotune_Remaps/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Autotune_Remaps
 * @subpackage Autotune_Remaps/admin
 * @author     Toby Wisener <toby@wisener.me>
 */
class Autotune_Remaps_Admin extends BaseClass {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		// Call the BaseClass constructor
        parent::__construct( $plugin_name, $version );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name.'_select2', plugin_dir_url( __FILE__ ) . 'css/select2.css', 
			array(), $this->version, 'all' );

		wp_enqueue_style( $this->plugin_name.'_ui_select', plugin_dir_url( __FILE__ ) . 'css/select.min.css', 
			array(), $this->version, 'all' );

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/autotune-remaps-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
/*
		wp_enqueue_script( $this->plugin_name.'_selec2', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array( 'jquery' ), $this->version, false );
*/
	}

	/**
	 * Register a custom admin menu page with a dashboard icon.
	 */
	public function register_admin_menu_page() {
	    add_menu_page(
	        __('Autotune Tuning System', 'textdomain' ),
        'Autotune Remaps',
        'manage_options',
        'autotune_remaps',
        array(&$this, 'autotune_admin_remap_page'),
	        'dashicons-performance',
	        6
	    );
	}

	/**
	 * Handle the submitting of the create charge form
	 *
	 * @since    1.0.0
	 */
	public function handle_admin_create_charge() {
		global $wp_session, $wpdb;
		
		// Ensure the form was submitted
	    if( !isset( $_POST["autotune_charge_type"] ) ) {
	    	return;
	    }

	    // Ensure the Charges tab gets opened by default
		$wp_session['open_tab'] = "ChargesTab";
	    
		// Validate
		$wp_session['autotune_submit_charge_errors'] = [];
		$wp_session['autotune_submit_remap_id'] = 0;


		if(!isset($_POST["autotune_charge_user_id"]) || intval($_POST["autotune_charge_user_id"]) == 0) {
			$wp_session['autotune_submit_charge_errors']['User'] = "Please specify a valid user";
		}

		if(strlen($_POST["autotune_price"]) == 0) {
			$wp_session['autotune_submit_charge_errors']['Price'] = "Please specify a valid price";
		}

		if(count($wp_session['autotune_submit_charge_errors']) > 0) {
			// Guard clause to stop processing if any errors are found
			return; 
		}
		error_log(print_r($_FILES['autotune_file'],true));
		// Save uploaded file to the 'bin' directory within the plugin directory
		$target_name = NULL;
		if($_POST["autotune_charge_type"] == self::$TYPE['SERVICE'] && $_FILES['autotune_file']['size'] > 0) {
			chmod($this->target_dir, 0755);

			$ext = pathinfo($_FILES['autotune_file']['name'], PATHINFO_EXTENSION);
			$target_name = substr(md5(rand()), 0, 7) . "." . $ext;
			$full_target_path = $this->target_dir.$target_name;
			if (!move_uploaded_file($_FILES["autotune_file"]["tmp_name"], $full_target_path)) {
				$wp_session['autotune_submit_remap_errors']['Error'] = "Upload failed - please try again later.";
				return;
			}
		}

		$data = array(
			'user_id' => $_POST["autotune_charge_user_id"], 
			'type' => $_POST['autotune_charge_type'],
			'status' => self::$STATUS["PAYMENT"],
			'remap_file' => $target_name,
			'price' => $_POST['autotune_price'],
			'other_notes' => $_POST["autotune_other_notes"],
			'created_at' => date('Y-m-d H:i:s')
			);
		$format = array('%s','%d');
		$wpdb->insert($this->db_table_name,$data,$format);
		$new_remap_id = $wpdb->insert_id;

		if(is_int($new_remap_id) && $new_remap_id > 1 /* The insert remap succeeded */) {
			$wp_session['autotune_submit_remap_id'] = 1 /* Don't send the real remap ID to the front end */;

			$User = get_user_by('id', get_current_user_id());

			// Don't send an email to requesting user

			// Prevent the reload-submit issue by refreshing the page
			header('Location: '.$_SERVER['REQUEST_URI']);
			$wp_session['open_tab'] = "ChargesTab";
			exit;
		}
	}

	/**
	 * Display the Manage Remaps page linked to the custom menu item.
	 */
	public function autotune_admin_remap_page(){
	    $templatePath = plugin_dir_path( __FILE__ ) . 'partials/autotune-remaps-admin-display.php';

		return include($templatePath);  
	}

    /**
     * Adds a user contact method field for CC remap emails to.
     *
     * @param $user_contact
     * @return mixed
     */
    public function custom_user_contact_methods($user_contact) {
        $user_contact['cc_remap_emails_to'] = 'CC Remap emails to:';
        return $user_contact;
    }

}
