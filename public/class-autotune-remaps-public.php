<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       wisener.me
 * @since      1.0.0
 *
 * @package    Autotune_Remaps
 * @subpackage Autotune_Remaps/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Autotune_Remaps
 * @subpackage Autotune_Remaps/public
 * @author     Toby Wisener <toby@wisener.me>
 */
class Autotune_Remaps_Public extends BaseClass {

	/**
	 * The validation errors for the submit remap form in the current request.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      array
	 */
	public static $submit_remap_errors = [];

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		// Call the BaseClass constructor
        parent::__construct( $plugin_name, $version );
	}

	/**
	 * Return the applicable label for a given remap status.
	 *
	 * @since    1.0.0
	 */
	public static function status($status) {
		return str_replace("_", " ", ucwords(strtolower(array_search($status, self::$STATUS))));
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/autotune-remaps-public.css', array(), $this->version, 'all' );

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/select.min.css', array(), $this->version, 'all' );

		// Load the dashicons library on the front end so customers can see the download icons etc 
		wp_enqueue_style( 'dashicons' );
	}

	/**
	 * Register the Angular app with the current page.
	 *
	 * @since    1.0.0
	 */
	public function add_ng_app_attr() {
	    $classes[] = '" ng-app="autotune';

	    return $classes;
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		
		// Including the Angular 1.7.9 Library
		wp_enqueue_script( $this->plugin_name . '_angular', plugin_dir_url( __FILE__ ) . 'js/angular.min.js');
		wp_enqueue_script( $this->plugin_name . '_angular_sanitize', plugin_dir_url( __FILE__ ) . 'js/angular.sanitize.min.js');
		wp_enqueue_script( $this->plugin_name . '_angular_ui_select', plugin_dir_url( __FILE__ ) . 'js/select.min.js');

		// Include the Angular library files
		wp_enqueue_script( $this->plugin_name . '_ngapp', plugin_dir_url( __FILE__ ) . 'js/app.js');
		wp_enqueue_script( $this->plugin_name . '_ng_file_directive', plugin_dir_url( __FILE__ ) . 'js/directives/FileDirective.js');
		wp_enqueue_script( $this->plugin_name . '_ng_tuning_controller', plugin_dir_url( __FILE__ ) . 'js/controllers/TuningController.js');
		wp_enqueue_script( $this->plugin_name . '_ng_sub_remap_controller', plugin_dir_url( __FILE__ ) . 'js/controllers/SubmitRemapController.js');
		wp_enqueue_script( $this->plugin_name . '_ng_mng_remap_controller', plugin_dir_url( __FILE__ ) . 'js/controllers/MyRemapsController.js');
		wp_enqueue_script( $this->plugin_name . '_ng_admin_remap_controller', plugin_dir_url( __FILE__ ) . 'js/controllers/AdminRemapsController.js');
		wp_enqueue_script( $this->plugin_name . '_ng_admin_charges_controller', plugin_dir_url( __FILE__ ) . 'js/controllers/AdminChargesController.js');
		wp_enqueue_script( $this->plugin_name . '_ng_tuning_service', plugin_dir_url( __FILE__ ) . 'js/services/TuningService.js');

		// Include the public-facing vanilla JavaScript code
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/autotune-remaps-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Return the content type string to be used within emails
	 *
	 * @since    1.0.0
	 */
	public function get_email_html_content_type() {
		return "text/html";
	}

	/**
	 * Display the end user form for requesting a remap
	 *
	 * [autotune_submit_remap]
	 * @since    1.0.0
	 */
	public function submit_remap_form() {
		if(!( wp_get_current_user() instanceof WP_User ) || wp_get_current_user()->ID == 0) {
			return "[Please login to request a remap online]";
		}

		return $this->template(plugin_dir_path( __FILE__ ) . 'partials/autotune-remaps-request-remap-display.php');
	}

	/**
	 * Handle the submitting of the request remap form
	 *
	 * @since    1.0.0
	 */
	public function handle_submit_remap_form() {
		global $wp_session, $wpdb;

		// Ensure the form was submitted
	    if( !isset( $_POST["autotune_manufacturer"] ) ) {
	    	return;
	    }
	    
		// Validate
		$wp_session['autotune_submit_remap_errors'] = [];
		$wp_session['autotune_submit_remap_id'] = 0;

		if(strlen($_POST["autotune_manufacturer"]) == 0) {
			$wp_session['autotune_submit_remap_errors']['Manufacturer'] = "Please specify a valid manufacturer";
		}

		if(strlen($_POST["autotune_model"]) == 0) {
			$wp_session['autotune_submit_remap_errors']['Model'] = "Please specify a valid model";
		}

		if(strlen($_POST["autotune_year"]) != 4) {
			$wp_session['autotune_submit_remap_errors']['Year'] = "Please specify a valid year";
		}

		if(strlen($_POST["autotune_engine_size"]) == 0) {
			$wp_session['autotune_submit_remap_errors']['Engine Type'] = "Please specify a valid engine type";
		}

		if($_FILES["autotune_file"]["size"] == 0 || $_FILES["autotune_file"]["name"] == "") {
	    	$wp_session['autotune_submit_remap_errors']['File'] = "Please choose a valid ECU file to upload";
	    }

		if(strlen($_POST["autotune_ecu_type"]) == 0) {
			$wp_session['autotune_submit_remap_errors']['ECU Type'] = "Please specify a valid ECU Type";
		}

        if(get_current_user_id() == 0) {
            $wp_session['autotune_submit_remap_errors']['User'] = "Cannot detect User ID, please check cookies are enabled and your browser is modern enough.";
        }

		if(count($wp_session['autotune_submit_remap_errors']) > 0) {
			// Guard clause to stop processing if any errors are found
			return; 
		}

		// Save uploaded file to the 'bin' directory within the plugin directory
		@chmod($this->target_dir, 0755);
		
		$ext = pathinfo($_FILES['autotune_file']['name'], PATHINFO_EXTENSION);
		$target_name = substr(md5(rand()), 0, 7) . "." . $ext;
		$full_target_path = $this->target_dir.$target_name;
		if (!move_uploaded_file($_FILES["autotune_file"]["tmp_name"], $full_target_path)) {
			$wp_session['autotune_submit_remap_errors']['Error'] = "Upload failed - please try again later.";
			return;
		}

		// Insert
		$data = array(
			'user_id' => get_current_user_id(), 
			'status' => self::$STATUS["PENDING"],
			'manufacturer' => $_POST["autotune_manufacturer"],
			'model' => $_POST["autotune_model"],
			'year' => $_POST["autotune_year"],
			'engine_size' => $_POST["autotune_engine_size"],
			'remap_file' => $target_name,
			'ecu_type' => $_POST["autotune_ecu_type"],
			'price' => NULL,
			'performance_tuning' => isset($_POST["autotune_performance_tuning"]),
			'lambda_o2_decat' => isset($_POST["autotune_lambda_o2_decat"]),
			'dpf_removal' => isset($_POST["autotune_dpf_removal"]),
			'adblue_scr_nox' => isset($_POST["autotune_adblue_scr_nox"]),
			'inlet_swirl_throttle' => isset($_POST["autotune_inlet_swirl_throttle"]),
			'egr_removal' => isset($_POST["autotune_egr_removal"]),
			'dtc' => isset($_POST["autotune_dtc"]),
			'dtc_p_codes' => $_POST["autotune_dtc_p_codes"],
			'other_notes' => $_POST["autotune_other_notes"],
			'created_at' => date('Y-m-d H:i:s')
			);
		$format = array('%s','%d');
		$wpdb->insert($this->db_table_name,$data,$format);
		$new_remap_id = $wpdb->insert_id;
		
		$updated_filename = pathinfo($_FILES['autotune_file']['name'], PATHINFO_FILENAME) . "_" . $new_remap_id;
		
		rename($full_target_path,  $this->target_dir.$updated_filename.".".$ext);
		
		$wpdb->update($this->db_table_name, array('remap_file'=> $updated_filename .".". $ext), array('remap_id'=>$new_remap_id ));/*update the filename in DB to correct format */

		if(is_int($new_remap_id) && $new_remap_id > 1 /* The insert remap succeeded */) {
			$wp_session['autotune_submit_remap_id'] = 1 /* Don't send the real remap ID to the front end */;

			$User = get_user_by('id', get_current_user_id());

			$remap_queue_position = $this->get_remap_position_in_queue($new_remap_id, $data["created_at"]);

			// Send an email to requesting user
			$this->sendMail("REQUESTED-USER", 
				$User->user_email, 
				[ 
					"remap" => $data,
					"ecu_file_size" => $_FILES["autotune_file"]["size"],
					"queue_position" => $remap_queue_position,
					"remaps_link" => $this->getBaseUrl() . "/my-account"
				 ]);

			// Send an email to the admin email
			$this->sendMail("REQUESTED-ADMIN", 
				$this->getAdminRemapEmail(), 
				[ 
					"remap" => $data,
					"ecu_file_size" => $_FILES["autotune_file"]["size"],
					"user" => $User,
					"queue_position" => $remap_queue_position,
					"remaps_link" => $this->getBaseUrl() . "/my-account",
					"download_link" => $this->get_remap_download_link_original($new_remap_id)
				 ]
				);

			// Prevent the reload-submit issue by refreshing the page
			header('Location: '.$_SERVER['REQUEST_URI']);
			exit;
		}
	}

	/**
	 * Handle the submitting of the updated map file within the admin screen
	 *
	 * @since    1.0.0
	 */
	public function handle_admin_upload_map() {
		global $wp_session, $wpdb;

	    if( !isset( $_POST["autoune_remap_id"] ) ) {
	    	return;
	    }

	    // Fetch the Remap resource from storage
	    $remap = $this->get_remap($_POST["autoune_remap_id"]);

	    // Save uploaded file
		$full_target_path = $this->target_dir.self::$completed_salt.$remap->remap_file;

		if (!move_uploaded_file($_FILES["autotune_updated_ecu"]["tmp_name"], 
			$full_target_path)) {
			$wp_session['autotune_submit_remap_errors']['Error'] = "Upload failed - please try again later.";
			return;
		}

		// The remap will always jump straight to complete after admin uploads map
		$updatedStatus = self::$STATUS['COMPLETE'];

		// Update the remap to PAYMENT or COMPLETED status
		$wpdb->update( $this->db_table_name, [
			'status' => $updatedStatus,
			'updated_at' => date('Y-m-d H:i:s')
		], 
		[	'remap_id' => $remap->remap_id ]  
		);		

		// Get the user and the updated remap
		$remap_owner = get_user_by('id', $remap->user_id);

		// Send an email depending on the new status of the remap
		switch($updatedStatus) {

			case self::$STATUS['PAYMENT']:
				// Send and email to the user requesting payment

				if(in_array( 'contributor', $remap_owner->roles )){
					$remap->price = "";
				}
				$this->sendMail("PAYMENT-USER",
					$remap_owner->user_email,
					[
						"remap" => $remap,
						"remaps_link" => $this->getBaseUrl() . "/my-account",
						"isContributor"=> $isContributor
					]
				);
			break;

			case self::$STATUS['COMPLETE']:
				// Send and email to the user confirming the completion of their remap

				$this->sendMail("COMPLETE-USER", 
					$remap_owner->user_email, 
					[ 
						"remap" => $remap,
						"remaps_link" => $this->getBaseUrl() . "/my-account",
						"download_link" => $this->get_remap_download_link($remap->remap_id)
					]
				);

			break;
		}
	
	}

	/**
	 * Display the Angular checker widget
	 *
	 * [autotune_checker]
	 * @since    1.0.0
	 */
	public function display_checker() {

		return $this->template(plugin_dir_path( __FILE__ ) . 'partials/autotune-remaps-checker-display.php');
	}

	/**
	 * Display the end user their current list of remaps
	 *
	 * @since    1.0.0
	 */
	public function display_my_remaps() {
		if(!( wp_get_current_user() instanceof WP_User ) || wp_get_current_user()->ID == 0) {
			return "[Please login to view your requested remaps]";
		}

		return $this->template(plugin_dir_path( __FILE__ ) . 'partials/autotune-remaps-my-remaps-display.php');
	}

	/**
	 *  Return the current url of the script.
	 *
	 * @since    1.0.0
	 */	
	public function current_url() {
		return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	}

	/**
	 *  Register the route endpoints for the internal API communication from the front end.
	 *
	 * @since    1.0.0
	 */
	public function register_api() {

        register_rest_route( // Resource for PUT a Remap (admin)
	    	'autotune-remaps/v1', '/remaps',
	    	[ 'methods' => WP_REST_Server::EDITABLE, 'callback' => [$this, 'api_update_remap'],
	    	'args' => [
					'remap_id' => [ 'validate_callback' =>  [ $this, 'api_validate_update_remap' ]],
					'price' => [ 'validate_callback' =>  [ $this, 'api_validate_update_remap' ]],
			        'autotune_note'=>['validate_callback' =>[$this, 'api_validate_update_remap']]
				],'permission_callback' => '__return_true'

		    ]
		);

        register_rest_route( // Resource for PUT all Remaps (admin)
	    	'autotune-remaps/v1', '/updatetestmofo',
	    	[ 'methods' => WP_REST_Server::EDITABLE, 'callback' => function($request) {
	    	    error_log("ekk");
	    	    return "lol";
	    	}, 'permission_callback' => '__return_true']
		);

		register_rest_route( // Resource for GET all Users (admin)
	    	'autotune-remaps/v1', '/users',
	    	[ 'methods' => 'GET', 'callback' => [$this, 'api_get_all_users'], 'permission_callback' => '__return_true' ]
		);

	    register_rest_route( // Resource for GET all Remaps (admin)
	    	'autotune-remaps/v1', '/remaps',
	    	[ 'methods' => 'GET', 'callback' => [$this, 'api_get_all_remaps'], 'permission_callback' => '__return_true' ]
		);

		register_rest_route( // Resource for GET all Remaps for the current user
	    	'autotune-remaps/v1', '/user/(?P<id>\d+)/remaps',
	    	[ 'methods' => 'GET', 'callback' => [$this, 'api_get_all_user_remaps'], 'permission_callback' => '__return_true' ]
		);

		register_rest_route( // Resource for PUT all Remaps (admin)
	    	'autotune-remaps/v1', '/remaps/all',
	    	[ 'methods' => WP_REST_Server::EDITABLE, 'callback' => [$this, 'api_batch_update_remaps'], 'permission_callback' => '__return_true' ]
		);

		register_rest_route( // Resource for GET all Remaps (admin export)
	    	'autotune-remaps/v1', '/remaps/all/(?P<id>\d+)',
	    	[ 'methods' => 'GET', 'callback' => [$this, 'api_batch_export_remaps'], 'permission_callback' => '__return_true' ]
		);
/*
        register_rest_route(
            'autotune-remaps/v1', '/remaps/user/(?P<id>\d+)',
            ['methods'=>'GET','callback' => [$this, 'api_batch_export_user_remaps'],'permission_callback'=>'__return_true'];
        );
*/
		register_rest_route( // Resource for POST IPN data from PayPal
	    	'autotune-remaps/v1', '/remaps/payment',
	    	[ 'methods' => 'POST', 'callback' => [$this, 'api_post_payment_callback'], 'permission_callback' => '__return_true' ]
		);

		register_rest_route( // Resource for downloading a completed/remapped ECU file
	    	'autotune-remaps/v1', '/remaps/(?P<id>\d+)/download',
	    	[ 'methods' => 'GET', 'callback' => [$this, 'api_get_remap_download_link'], 'permission_callback' => '__return_true' ]
		);

		register_rest_route( // Resource for downloading an originally uploaded ECU file
	    	'autotune-remaps/v1', '/remaps/(?P<id>\d+)/download/original',
	    	[ 'methods' => 'GET', 'callback' => [$this, 'api_get_original_remap_download_link'], 'permission_callback' => '__return_true' ]
		);

		register_rest_route( // Resource for POST charge
	    	'autotune-remaps/v1', '/charges',
	    	[ 'methods' => 'POST', 'callback' => [$this, 'api_post_charges'],'permission_callback' => '__return_true' ]
		);

	}

	/**
	 * Display the page with all the current remaps.
	 *
	 * @since    1.0.0
	 */
	public function display_admin_remaps() {
		if(!( wp_get_current_user() instanceof WP_User ) || wp_get_current_user()->ID == 0 || !current_user_can('administrator')) {
			return "[Please login as an administrator to view all remaps]";
		}

		return $this->template(plugin_dir_path( __FILE__ ) . 'partials/autotune-remaps-admin-display.php');
	}

	/**
	 *  API endpoint to get all users
	 *
	 * @since    1.0.0
	 */	
	public function api_get_all_users(WP_REST_Request $request, $output = ARRAY_A) {
		global $wpdb;

		return $wpdb->get_results( 
			"
			SELECT 
				ID as id,
				user_nicename AS text
			FROM ".$wpdb->prefix."users
			",
			$output
		);
	}

	/**
	 *  API endpoint to get all remaps
	 *
	 * @since    1.0.0
	 */	
	public function api_get_all_remaps(WP_REST_Request $request, $output = OBJECT,$user_id = "") {
        global $wpdb;
		$isExport = ($output == ARRAY_A /* ARRAY_A is only used during export */);
		$limit = "LIMIT 500"; // By default, only show 500 remaps
		if ($isExport) {
			if(!empty($user_id) && $user_id != 0) {
				$user_id_query = 'AND users.id =' . $user_id;
			}else{
				$user_id_query = "";
			}
			// For exports, show unlimited remaps
			$limit = "";
		}

		$results = $wpdb->get_results(
			"
			SELECT ".$this->db_table_name.".*,
			users.user_nicename AS username
			FROM ".$this->db_table_name." 
			LEFT JOIN ".$wpdb->prefix."users AS users ON users.id = ".$this->db_table_name.".user_id 
			WHERE status <> " . self::$STATUS['DELETED'] . "
			" . $user_id_query ." 
			AND type = " . self::$TYPE['REMAP'] . "
			ORDER BY remap_id DESC ".$limit,
			$output
		);
		return $results;
	}


	/**
	 *  API endpoint to get all remaps requested by the currently authenticated user
	 *
	 * @since    1.0.0
	 */	
	public function api_get_all_user_remaps(WP_REST_Request $request) {
		$url_params = $request->get_url_params();

		$user_id = $url_params['id'];

		$user_remaps = $this->get_user_remaps($user_id);

		$total_amount = 0;

		// Sum up the totals for this user
		foreach($user_remaps as $remap) {
			if(in_array($remap->status, 
				[ self::$STATUS["ARCHIVED"], self::$STATUS["DELETED"], self::$STATUS["PARKED"]] )) {
				continue; // Don't total Archived or Deleted remaps
			}

			switch($remap->type) {
				case self::$TYPE["REMAP"]:
				case self::$TYPE["SERVICE"]:
				case self::$TYPE["SUBSCRIPTION"]:
					$total_amount -= $remap->price;
				break;

				case self::$TYPE["PAYMENT"]:
					$total_amount += $remap->price;
				break;
			}
		}

		return [
			"total_amount" => $total_amount,
			"remaps" => $user_remaps
		];
	}

	/**
	 *  API endpoint to get all remaps
	 *
	 *	This function is reused for all params to improve code maintainability
	 *	A Switch statement is used to validate different parameters with each call to the method
	 *
	 * @since    1.0.0
	 */	
	function api_validate_update_remap($value, $request, $param) {

		switch($param) {

			case "remap_id":

				// Ensure the remap_id is an integer
				if(intval($value) == 0) return false;

				// Fetch the resource now and store it on the request for later use
				$request['resource'] = $this->get_remap($value);

				// Ensure the remap_id leads to a valid remap
				if($request['resource'] == NULL) return false;

			break;

			case "status":

				// Initial value wasn't an integer
				if(intval($value) != $value) return false;
				
				// Ensure value is a supported status
				if(!in_array($value, array_values(self::$STATUS))) return false;

				// Ensure there is a price set before going to payment
				if($value == self::$STATUS['payment'] && $request['price'] == NULL) return false;

			break;

			
		}
		
		
		return true; // Innocent if not proven guilty
	}

	/**
	 *  API endpoint to update a specific remap resource
	 *
	 * @since    1.0.0
	 */


    public function api_delete_remap($remap_id){

    }
	public function api_update_remap(WP_REST_Request $request) {
		global $wpdb;

		// You can get the combined, merged set of parameters:
		$req_params = $request->get_params();
		// Get the original remap resource from storage
		$original_remap = $this->get_remap($req_params['resource']->remap_id);


        if($req_params['status'] == self::$STATUS['DELETED']){
            $this->api_batch_delete_remaps($req_params['resource']->remap_id);
            return "";
        }
		$update_array = [
			'price' => $req_params['price'],
			'updated_at' => date('Y-m-d H:i:s')
		];

		if($original_remap->status != $req_params['status']) {
			$update_array['status'] = $req_params['status'];
		}

		if($original_remap->autotune_note != $req_params['autotune_note']){
			$update_array['autotune_note'] = $req_params['autotune_note'];
		}



		$wpdb->update( $this->db_table_name, $update_array, 
		[ 'remap_id' => $req_params['resource']->remap_id ]  
		);		

		// Get the user who requested the remap
		$remap_owner = get_user_by('id', $original_remap->user_id);

		// Get the updated remap resource from storage
		$updated_remap = $this->get_remap($req_params['resource']->remap_id);

		if($original_remap->type == self::$TYPE['REMAP'] 
			&& $original_remap->status != $req_params['status']) {
			// The status of this email has been updated

			// Send an email depending on the new status of the remap
			switch($req_params['status']) {

				case self::$STATUS['IN_PROGRESS']:
					// Send and email to the user requesting payment

					$this->sendMail("PROGRESS-USER", 
						$remap_owner->user_email, 
						[ 
							"remap" => $updated_remap,
							"remaps_link" => $this->getBaseUrl() . "/my-account"
						]
					);
				break;

				case self::$STATUS['PAYMENT']:
					// Send and email to the user requesting payment

					$this->sendMail("PAYMENT-USER", 
						$remap_owner->user_email, 
						[ 
							"remap" => $updated_remap,
							"remaps_link" => $this->getBaseUrl() . "/my-account"
						]
					);
				break;

				case self::$STATUS['COMPLETE']:
					// Send and email to the user confirming the completion of their remap

					$this->sendMail("COMPLETE-USER", 
						$remap_owner->user_email, 
						[ 
							"remap" => $updated_remap,
							"remaps_link" => $this->getBaseUrl() . "/my-account",
							"download_link" => $this->get_remap_download_link($updated_remap->remap_id)
						]
					);

				break;
			}

		}
		

		// Return the freshly updated resource to update the front end
		return $updated_remap;
	}

	/**
	 *  API endpoint to batch update a given set of remaps to a specified status
	 *
	 * @since    1.0.0
	 */
	public function api_batch_update_remaps(WP_REST_Request $request) {
		global $wpdb;

		// You can get the combined, merged set of parameters:
		$req_params = $request->get_params();

		$user_id = $req_params['user_id'];
		$update_ids = implode(',', $req_params['remap_ids']);
		$status = $req_params['status'];
        if($status == self::$STATUS['DELETED']){
                $this->api_batch_delete_remaps($update_ids);
        }else {
            $wpdb->query("UPDATE " . $this->db_table_name . "
			SET status = '" . $status . "'
			WHERE remap_id IN (" . $update_ids . ")");
        }

		// Return freshly updated rows for front end consolidation, if user_id = 0 then get all remaps
		return $user_id != 0 ? $this->get_user_remaps($user_id)
			:$this->api_get_all_remaps($request);
	}

    function api_batch_delete_remaps($update_ids){
        global $wpdb;

        $remaps = $wpdb->get_results("SELECT * FROM ". $this->db_table_name ." WHERE remap_id IN (".$update_ids.")" );

        // Ids of remap files which have been successfully deleted
        $remaps_for_deletion = [];

        //get permissions (only need to do it once)
        @chmod($this->target_dir, 0755);

        foreach ($remaps as $remap){
            // get file path
            $full_target_path = $this->target_dir.$remap->remap_file;

            if(file_exists($full_target_path) && unlink($full_target_path)) {
                $completed_full_target_path = $this->target_dir.'completed_'.$remap->remap_file;
                unlink($completed_full_target_path);
            }
            $remaps_for_deletion[] = $remap->remap_id;
        }

        // Do this outside the foreach loop
        $wpdb->query("DELETE FROM " . $this->db_table_name. " WHERE remap_id IN (".implode(",", $remaps_for_deletion).")");
    }

	function api_batch_export_remaps(WP_REST_Request $request) {
        global $wp_session,$wpdb;

        $url_params = $request->get_url_params();

        $user_id = $url_params['id'];
		// Give us the remap data as an associative array
		$remaps = $this->api_get_all_remaps($request, ARRAY_A,$user_id);
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Hello World!');

		// Headers
		$sheet->fromArray(array_keys($remaps[0]),NULL,'A1');
		// Data
		$sheet->fromArray($remaps, NULL, 'A2');

		// Return freshly updated rows for front end consolidation
		$writer = new Xlsx($spreadsheet);

		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="export.xlsx"');
            
        $writer->save("php://output");
        exit;
	}

	/**
	 *  API endpoint to get the download link of a successfully completed Remap file
	 *
	 * @since    1.0.0
	 */	
	function api_get_remap_download_link(WP_REST_Request $request) {

		$url_params = $request->get_url_params();

		$remap = $this->get_remap($url_params['id']);

		if($remap == NULL) {
			return "[The remap you are trying to download could not be found]";
		}

		if($remap->status != self::$STATUS['COMPLETE'] && $remap->status != self::$STATUS['PARKED']) {
			return "[This remap is not yet completed]";
		}

		$full_target_path = $this->target_dir.self::$completed_salt.$remap->remap_file;
		$mime_type = mime_content_type($full_target_path);
		
		header('Content-Type: ' . $mime_type);
		header("Content-Transfer-Encoding: Binary; charset=ansi"); 
		header("Content-Disposition: attachment; filename=\"" . $this->get_remap_filename($remap /* Completed */) . "\"");
		readfile($full_target_path);
		exit;
	}

	/**
	 *  API endpoint to get the download link of an originally uploaded Remap file
	 *
	 * @since    1.0.0`
	 */	
	function api_get_original_remap_download_link(WP_REST_Request $request) {

		$url_params = $request->get_url_params();

		$remap = $this->get_remap($url_params['id']);

		if($remap == NULL) {
			return "[The remap you are trying to download could not be found]";
		}

		$full_target_path = $this->target_dir.$remap->remap_file;
		
		$mime_type = mime_content_type($full_target_path);
		
		header('Content-Type: ' . $mime_type);
		header("Content-Transfer-Encoding: Binary; charset=ansi"); 
		header("Content-Disposition: attachment; filename=\"" . $this->get_remap_filename($remap /* Not completed */) . "\"");
		readfile($full_target_path); 
		exit;
	}

	/**
	 *  API endpoint for handling payment IPN (PayPal) messages for a remap
	 *
	 * @since    1.0.0
	 */	
	function api_post_payment_callback(WP_REST_Request $request) {
		global $wpdb;

		// Set this to true to use the sandbox endpoint during testing:
		$enable_sandbox = false;
		
		$ipn = new PaypalIPN();
		if ($enable_sandbox) {
		    $ipn->useSandbox();
		}

		$verified = $ipn->verifyIPN();
		if (!$verified) {
			error_log("Payment not verified");
			return new WP_REST_Response(["message" => "Payment could not be verified by IPN"], 401);
		}

		if (strtolower($_POST["receiver_email"]) != self::$paypal_email) {
			error_log("Incorrect Receiver Email: " . $_POST["receiver_email"]);
			return new WP_REST_Response(["message" => "Incorrect Receiver Email"], 401);
	    }

	    // Find the Remap
	    $remap_id = explode('-', $_POST["item_name"])[1];
	    $remap = $this->get_remap($remap_id);
	    if($remap == NULL) {
	    	error_log("Remap not found: " . $_POST["item_name"]);
			return new WP_REST_Response(["message" => "Remap could not be found in the system"], 400);
	    }

		if ($_POST["mc_gross"] == $remap->price && 
			$_POST["mc_currency"] == "GBP" && 
			$_POST["payment_status"] == "Completed") {

		    // Update the remap record in storage to status 'COMPLETED'
		    $wpdb->update( $this->db_table_name, [
		    	'status' => self::$STATUS['COMPLETE'],
		    	'updated_at' => date('Y-m-d H:i:s')
		    ], 
		    [ 'remap_id' => $remap->remap_id ]  
		    );	

			// Get the user who requested the remap
			$remap_owner = get_user_by('id', $remap->user_id);

			// Get the updated remap resource from storage
			$updated_remap = $this->get_remap($remap->remap_id);

			$this->sendMail("COMPLETE-USER", 
				$remap_owner->user_email, 
				[ 
					"remap" => $updated_remap,
					"remaps_link" => $this->getBaseUrl() . "/my-account",
					"download_link" => $this->get_remap_download_link($remap->remap_id)
				]
			);
		}

		// Reply with an empty 200 response to indicate to paypal the IPN was received correctly;
	}

	function api_post_charges($request) {
		error_log(print_r($_POST, true));
		error_log(print_r($_FILES, true));
		return "gotti";
	}

}
