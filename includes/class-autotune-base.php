<?php

class BaseClass {
	
	/**
	 * Whether to DROP the database on plugin deactivation.
	 * (Not reccommended once real customer data is stored)
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public static $drop_db_deactivate = false;

	/**
	 * The paypal account email which receives payments.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public static $paypal_email = "info@auto-tune.co.uk";

	/**
	 * The admin email which receives remap notifications.
	 *
	 * If left blank, the system will use the default wordpress admin email.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	private static $remap_admin_email = "files@auto-tune.co.uk";

	/**
	 * The admin email which appears as the originator for email notifications.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private static $from_email = "info@auto-tune.co.uk";

	/**
	 * The database table name for managing remaps.
	 * This will be pre-pended with the current Wordpress Database Prefix in the constructor.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	protected $db_table_name = /* $wpdb->prefix */ "remaps";

	/**
	 * A secret string appended to the end of ecu filenames when they are completed.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	protected static $completed_salt = "completed_";

	/**
	 * The target directory of uploaded and updated remaps.
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
	protected $target_dir = "/bin/";

	/**
	 * The prefix for all API calls to this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
	protected $api_prefix = "/wp-json/autotune-remaps/v1";
	
	/**
	 * The status enum for remaps.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      integer    $STATUS    The status enum for remaps
	 */
	public static $STATUS = [
		"PENDING" => 0,
		"IN_PROGRESS" => 1,
		"PAYMENT" => 2,
		"COMPLETE" => 3,
		"ARCHIVED" => 4,
		"DELETED"  => 5,
		"PARKED" => 6
	];

	/**
	 * The enum for remap types.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      integer    $TYPE    The status enum for remaps
	 */
	public static $TYPE = [
		"REMAP" => 0,
		"SERVICE" => 1,
		"SUBSCRIPTION" => 2,
		"PAYMENT" => 3
	];

	/**
	 * The different types of emails sent out from the plugin and information associated with each.
	 *
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	protected static $mail_templates = [

		"REQUESTED-USER" => [
			"template" => "autotune-remap-requested-user.php",
			"subject" => "Your remap request has been received!"
		],

		"REQUESTED-ADMIN" => [
			"template" => "autotune-remap-requested-admin.php",
			"subject" => "New Remap Request!"
		],

		"PROGRESS-USER" => [
			"template" => "autotune-remap-progress-user.php",
			"subject" => "Your remap is in progress!"
		],

		"PAYMENT-USER" => [ 
			"template" => "autotune-remap-payment-user.php",
			"subject" => "Your remap is ready for payment!"
		],

		"COMPLETE-USER" => [
			"template" => "autotune-remap-complete-user.php",
			"subject" => "Your remap is now complete!"
		]

	];

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of this plugin.
	 */
	protected $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		global $wpdb;

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->target_dir = WP_PLUGIN_DIR.'/' . $this->plugin_name . $this->target_dir;

		// Prepend the Wordpres database prefix to our defined table name
		$this->db_table_name = $wpdb->prefix . $this->db_table_name;
	}

	/**
	 * Send a system email with a predefined email template and subject
	 *
	 * @since    1.0.0
	 */
	protected function sendMail($template, $to, $data, $cc = "") {

		// Enable HTML Emails so the templates can be sent out properly
		add_filter( 'wp_mail_content_type', [ $this, 'get_email_html_content_type' ]);

		// Select the correct template file name
		$template_file = self::$mail_templates[$template]["template"];

		// Specify the From: header to increase the authenticity of the email
		$headers = array(
            "From: Autotune <". self::$from_email .">",
            "Content-Type: text/html; charset=UTF-8"
        );

        // Check if $cc is not empty and contains valid email addresses
        if (!empty($cc)) {
            $headers[] = "Cc: " . $cc;
        }

		// Compile the email template with the data passed into this function
		$completedTemplate = $this->template(WP_PLUGIN_DIR.'/autotune-remaps/public/partials/emails/' . $template_file, $data);

		// Send the email using the base wordpress function
		$result = wp_mail( $to, self::$mail_templates[$template]["subject"], $completedTemplate, $headers, array() );
		if(!$result) {
			error_log("Email not sent to ($to)");
		}

		// Disable HTML Emails so that no conflicts arise with other plugins
		remove_filter( 'wp_mail_content_type', [ $this, 'get_email_html_content_type' ]);

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
	 * Compile a template specified at the path with a scope of data variables
	 *
	 * @since    1.0.0
	 */
	protected function template($templatePath, $data = NULL) {
		ob_start();
		include($templatePath);
		return ob_get_clean();
	}

	/**
	 * Return the current url without trailing backslash, i.e. https://auto-tune.co.uk
	 *
	 * @since    1.0.0
	 */
	protected function getBaseUrl() {
		$domain = $_SERVER['HTTP_HOST'];

		return 'https://' . $domain;
	}

	/**
	 * Return the full url for downloading a specific remap's finished ECU file.
	 *
	 * @since    1.0.0
	 */
	protected function get_remap_download_link($remap_id) {
		return $this->getBaseUrl() . $this->api_prefix . "/remaps/" . $remap_id . "/download";
	}

	/**
	 * Return the full url for downloading a specific remap's original ECU file.
	 *
	 * @since    1.0.0
	 */
	protected function get_remap_download_link_original($remap_id) {
		return $this->getBaseUrl() . $this->api_prefix . "/remaps/" . $remap_id . "/download/original";
	}

	/**
	 * Return the appropriate email to act as the admin for this plugin
	 *
	 * @since    1.0.0
	 */
	protected function getAdminRemapEmail() {
		if(self::$remap_admin_email == "" || self::$remap_admin_email == NULL) {
			return get_option('admin_email');
		}

		return self::$remap_admin_email;
	}

	/**
	 *  Function to retrieve a given remap from storage as a stdClass Object
	 *
	 * @since    1.0.0
	 */	
	protected function get_remap($remap_id) {
		global $wpdb;

		return $wpdb->get_row( "SELECT remaps.*, users.user_nicename username FROM " . $this->db_table_name . " remaps 
			INNER JOIN {$wpdb->prefix}users users ON users.ID = remaps.user_id WHERE remap_id = " . $remap_id );
	}

	/**
	 *  Function to get all remaps for a given user
	 *
	 * @since    1.0.0
	 */	
	public function get_user_remaps($user_id) {
		global $wpdb;

		return $wpdb->get_results( 
			"
			SELECT *
			FROM ".$this->db_table_name."
			WHERE user_id = '".$user_id."'
				AND status <> ".self::$STATUS["DELETED"]."
			ORDER BY updated_at DESC
			"
		);
	}

	/**
	 *  Function to return the appropriate file name for a given remap
	 *
	 * @since    1.0.0
	 */
	protected function get_remap_filename($remap) {

		$ext = pathinfo($remap->remap_file, PATHINFO_EXTENSION);
		$filename = pathinfo($remap->remap_file,PATHINFO_FILENAME).".".$ext;

		return $filename;
	}

	/**
	 *  Function to return a given remap's position in the workload queue
	 *  (Number of remaps in PENDING status, which were requested before $remap_created_at)
	 *
	 * @since    1.0.0
	 */	
	protected function get_remap_position_in_queue($remap_id, $remap_created_at) {
	    global $wpdb;

	    // Find PENDING remaps which were created prior to this remap 
	    $rowcount = $wpdb->get_var("SELECT COUNT(*) FROM " . $this->db_table_name . " 
	    	WHERE status = '" . self::$STATUS['PENDING'] . "' 
	    		AND created_at < '" . $remap_created_at . "'
	    		AND remap_id != '" . $remap_id ."'
                AND user_id != '0'");

	    return $rowcount + 1 /* Plus 1 because those remaps must be completed first */;
	}

}