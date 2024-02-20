<?php

class ApiMarketServiceFailException extends Exception {
    public function __construct(string $message = "", $response = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->response = $response;
    }
}

/**
 * @throws ApiMarketServiceFailException
 */
function apimarket_remote_post($url, $args) {
    $cached_response = get_transient($url);
    if (false !== $cached_response) {
        return $cached_response;
    }
    $response = wp_remote_post($url, $args);
    if (is_wp_error($response)) {
        throw new ApiMarketServiceFailException("Something goes wrong with ApiMarket.", $response->get_error_messages()[0]);
    }
    $result_body = wp_remote_retrieve_body($response);
    if (!empty($result_body)) {
        $json = json_decode($result_body,true);
        if (!$json['success']) {
            throw new ApiMarketServiceFailException("Something goes wrong with ApiMarket.", json_encode($json));
        }
        set_transient($url, $json, 12 * HOUR_IN_SECONDS);
        return $json;
    }
    set_transient($url, $response, 12 * HOUR_IN_SECONDS);
    return $response;
}

class Cf7_To_Any_Api {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Cf7_To_Any_Api_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

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
		if ( defined( 'CF7_TO_ANY_API_VERSION' ) ) {
			$this->version = CF7_TO_ANY_API_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'cf7-to-any-api';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Cf7_To_Any_Api_Loader. Orchestrates the hooks of the plugin.
	 * - Cf7_To_Any_Api_i18n. Defines internationalization functionality.
	 * - Cf7_To_Any_Api_Admin. Defines all hooks for the admin area.
	 * - Cf7_To_Any_Api_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cf7-to-any-api-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cf7-to-any-api-i18n.php';

		/**
		 * The class responsible for defining Custom WP List Table For Log display
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cf7-to-any-api-log-list-table.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cf7-to-any-api-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/client_asset_loaders.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/config.php';

		$this->loader = new Cf7_To_Any_Api_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Cf7_To_Any_Api_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Cf7_To_Any_Api_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Cf7_To_Any_Api_Admin($this->get_plugin_name(), $this->get_version());
		$api_mail_hook = $this->setting_get_options();

		$this->loader->add_action('admin_notices', $plugin_admin, 'cf7_to_any_api_verify_dependencies');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
		$this->loader->add_action('init', $plugin_admin,'cf7anyapi_custom_post_type', 10);
		$this->loader->add_action('add_meta_boxes', $plugin_admin,'cf7anyapi_metabox');
		$this->loader->add_action('save_post',$plugin_admin,'cf7anyapi_update_settings',10,2);
		$this->loader->add_action('wp_ajax_cf7_to_any_api_get_form_field',$plugin_admin,'cf7_to_any_api_get_form_field_function');
		$this->loader->add_action('wp_ajax_cf7_to_any_api_bulk_log_delete',$plugin_admin,'cf7_to_any_api_bulk_log_delete_function');
		if($api_mail_hook['cf7_to_api_before_mail_sent']){
			$cf7_filter_hook = 'wpcf7_before_send_mail';
		}else{
			$cf7_filter_hook = 'wpcf7_mail_sent';
		}
		$this->loader->add_action($cf7_filter_hook,$plugin_admin,'cf7_to_any_api_send_data_to_api');
		$this->loader->add_action('admin_menu', $plugin_admin, 'cf7anyapi_register_submenu', 90);
		$this->loader->add_filter('plugin_action_links',$plugin_admin,'cf7anyapi_add_settings_link',10,2);
		$this->loader->add_filter('manage_cf7_to_any_api_posts_columns',$plugin_admin,'cf7_to_any_api_filter_posts_columns');
		$this->loader->add_action('manage_cf7_to_any_api_posts_custom_column',$plugin_admin,'cf7_to_any_api_table_content',10,2);
		$this->loader->add_filter('manage_edit-cf7_to_any_api_sortable_columns',$plugin_admin,'cf7_to_any_api_sortable_columns');
		$this->loader->add_action('plugins_loaded',$plugin_admin,'cf7toanyapi_add_new_table',10, 2);
		$this->loader->add_action('wp_ajax_delete_records',$plugin_admin,'delete_cf7_records',10, 2);

		$this->loader->add_filter('plugin_row_meta', $plugin_admin,'cf7_api_plugin_row_meta', 10, 2 );

        $this->loader->add_action( 'admin_post_save_cf7_to_any_api_update_settings',$plugin_admin,'cf7_to_any_api_update_settings');

        $this->loader->add_action('wpcf7_editor_panels', $plugin_admin, 'cf7_to_any_api_manage_from_cf7_panel', 3 );

        $this->loader->add_action('wpcf7_after_create', $plugin_admin, 'wpcf7_after_create', 3);

        $this->loader->add_action('wpcf7_after_update', $plugin_admin, 'wpcf7_after_update', 3);

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
        add_filter('wpcf7_verify_nonce', '__return_true' );
        add_action('wp_enqueue_scripts', 'jvcf7p_client_assets', 20);
        add_action('wp_ajax_nopriv_jvcf7p_ajax_validation', 'jvcf7p_ajax_validation');
        add_action('wp_ajax_jvcf7p_ajax_validation', 'jvcf7p_ajax_validation');
        add_action('wpcf7_mail_sent', 'jvcf7p_wpcf7_mail_sent', 10, 1 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
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
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Cf7_To_Any_Api_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
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

    public function find_apimarket_service_from_cf7($cf7_form_id)
    {
        $args = array(
            'post_type' => 'cf7_to_any_api',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'meta_query' => array('relation' => 'AND', array('key' => 'cf7anyapi_selected_form', 'value' => $cf7_form_id, 'compare' => '=',),),
        );
        $related_posts = get_posts($args);
        if (!empty($related_posts)) {
            return $related_posts[0];
        }
       return null;
    }

    /*
   *
   * @since    2.5.0
   */
    public function cf7_to_any_api_manage_from_cf7_panel($panels)
    {
        $new_page = array(
            'Integration' => array(
                'title' => 'ApiMarket',
                'callback' =>  array ($this, 'load_cf7_panel')
            )
        );
        return array_merge($panels, $new_page);
    }

    public function load_cf7_panel($cf7_form)
    {
        $_POST['contact_form7_form_id'] = $cf7_form->id();
        include dirname(__FILE__) . '/partials/cf7-to-any-api-admin-cf7-panel.php';
    }

    public function wpcf7_after_create($contact_form)
    {
        $_POST['cf7anyapi_selected_form'] = (string)$contact_form->id;
        $_POST['cf7anyapi_json_format'] = json_encode(['curp' => '[curp]']);
        wp_insert_post(array(
            'post_title'    => 'form-'.$contact_form->title().'-curp',
            'post_status'   => 'publish',
            'post_type'     => 'cf7_to_any_api'
        ));
    }

    public function wpcf7_after_update($contact_form)
    {
        $_POST['cf7anyapi_selected_form'] = (string)$contact_form->id;
        wp_update_post(array(
            'post_title'    => 'form-'.$contact_form->title().'-curp',
            'post_status'   => 'publish',
            'post_type'     => 'cf7_to_any_api'
        ));
    }

	/**
	 * Retrieve the all current Post API data
	 *
	 * @since     1.0.0
	 * @return    string
	 */
	public function Cf7_To_Any_Api_get_options($apimarket_post) {
		$options = [];
		$field_array = array();
        $options['cf7anyapi_id'] = $apimarket_post->ID;
        $options['cf7anyapi_title']  = $apimarket_post->post_title;
		$options['cf7anyapi_selected_form'] = get_post_meta($apimarket_post->ID,'cf7anyapi_selected_form',true);
		$options['cf7anyapi_base_url'] = get_post_meta($apimarket_post->ID,'cf7anyapi_base_url',true);
		$options['cf7anyapi_basic_auth'] = get_post_meta($apimarket_post->ID,'cf7anyapi_basic_auth',true);
		$options['cf7anyapi_bearer_auth'] = get_post_meta($apimarket_post->ID,'cf7anyapi_bearer_auth',true);
		$options['cf7anyapi_input_type'] = get_post_meta($apimarket_post->ID,'cf7anyapi_input_type',true);
		$options['cf7anyapi_method'] = get_post_meta($apimarket_post->ID,'cf7anyapi_method',true);
		$options['cf7anyapi_json_format'] = get_post_meta($apimarket_post->ID,'cf7anyapi_json_format',true);
		$options['cf7anyapi_header_request'] = get_post_meta($apimarket_post->ID,'cf7anyapi_header_request',true);
		if(!empty($options['cf7anyapi_selected_form'])){
			$ContactForm = WPCF7_ContactForm::get_instance($options['cf7anyapi_selected_form']);
			$form_fields = $ContactForm->scan_form_tags();
			foreach($form_fields as $form_fields_value){
				if($form_fields_value->basetype != 'submit'){
					$field_array[$form_fields_value->raw_name] = (isset($options['cf7anyapi_form_field'][$form_fields_value->raw_name]) ? $options['cf7anyapi_form_field'][$form_fields_value->raw_name] : '');
				}
			}
			$options['cf7anyapi_form_field'] = $field_array;
		}
		return $options;
	}


	/**
	 * Retrieve the all current Setting data
	 *
	 * @since     1.1.2
	 * @return    array
	 */
	public static function setting_get_options(){
		$setting_options = [];
		$setting_options['cf7_to_api_before_mail_sent'] = get_option('cf7_to_api_before_mail_sent');
        $setting_options['cf7_to_api_default_header'] = get_option('cf7_to_api_default_header');
		return $setting_options;
	}

	/**
	 * Saved Form Fields show by default
	 *
	 * @since     1.0.0
	 * @return    string
	 */
	public function Cf7_To_Any_Api_default_form_field($form_id){
		$data = array();
		$form_ID     = (int)stripslashes($form_id); # change the 80 to your CF7 form ID 
		$ContactForm = WPCF7_ContactForm::get_instance( $form_ID );
		
		if($ContactForm){
			$form_fields = $ContactForm->scan_form_tags();
    		$data['status'] = 200;
    		$data['fields'] = $form_fields;
    	}
    	else{
    		$data['status'] = 404;
    		$data['fields'] = 'No form Found';
    	}
		return $data;
	}

	public function cf7toanyapi_sortdata($data){
	    $data_sorted = array();
		//Set submitted id wise form information
	    foreach ($data as $k => $v) {
	        if(!isset($data_sorted[$v->data_id])){
	            $data_sorted[$v->data_id] = array();
	        }
	        $data_sorted[$v->data_id][$v->field_name] = (string) apply_filters('cf7toanyapi_entry_value', trim(wp_unslash($v->field_value)), $v->field_name);
	    }

	    return $data_sorted;
	}

	public function cf7toanyapi_get_db_fields($fid, $filter = true){

	    global $wpdb;
		$fid = (int)$fid;
		$data_entry_table_name = sanitize_text_field($wpdb->prefix.'cf7anyapi_entries');
	    $sql = $wpdb->prepare("SELECT `field_name` FROM `{$data_entry_table_name}` WHERE form_id = %d GROUP BY `field_name` ORDER BY `id`", $fid);
	    $data = $wpdb->get_results($sql);
	    $fields = array();
		if(!empty($data)){
			foreach ($data as $k => $v) {
				if(defined('vsz_cf7_display_ip')){
					if($v->field_name != 'submit_ip'){
						$fields[$v->field_name] = htmlspecialchars_decode($v->field_name);
					}
				}
				else{
					$fields[$v->field_name] = htmlspecialchars_decode($v->field_name);
				}
			}
		}

		//Check if filter is true or not
	    if ($filter) {
			//Get all fields information as per Setting screen
	        $fields = (array) apply_filters('cf7toanyapi_admin_fields', $fields, $fid);
	    }

	    return $fields;
	}

	public function cf7toanyapi_admin_get_field_name($field){
	    return esc_html($field);
	}

	public function cf7toanyapi_field_type_info($fid){

		if(empty($fid) || !intval($fid)) return ;

		$fid = intval($fid);
		$obj_form = self::cf7toanyapi_get_the_form_list($fid);
		//get pre define fields information
		$arr_form_tag = $obj_form[0]->scan_form_tags();
		$arr_field_type = array();
		if(!empty($arr_form_tag)){
			//Get all fields related information
			foreach($arr_form_tag as $key => $arr_type){
				//Check if tag type is submit then ignore tag info
				if($arr_type['basetype'] == 'submit') continue;
				//get field type information
				$arr_field_type[$arr_type['name']] = $arr_type['basetype'];
			}
		}

		return $arr_field_type;
	}

	public function cf7toanyapi_get_the_form_list($fid = ''){

		//Get All form information
		$forms = WPCF7_ContactForm::find();
		$form = array();
		//fetch each form information

		foreach ($forms as $k => $v){
			//Check if form id not empty then get specific form related information
			if(!empty($fid)){
				if($v->id() == $fid){
					$form[] = $v;
					return $form;
				}
			}
			else{
				$form[] = $v;
			}
	    }

		if(count($form)>1){
			// New function Added to sort the array by CF7 Name
			usort($form, "cmp_sort_form_name");
		}

		return $form;
	}
}