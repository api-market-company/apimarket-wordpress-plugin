<?php
function jvcf7p_client_assets(){
    wp_register_style('jvcf7p_client_css', plugins_url('apimarket/public/css/jvcf7p_client.css'), array(), $GLOBALS['jvcf7p_current_version']);
    wp_enqueue_style('jvcf7p_client_css');
    wp_enqueue_script('jvcf7p_jquery_validate', plugins_url('apimarket/public/js/jquery.validate.min.js'), array('jquery'), $GLOBALS['jvcf7p_current_version'], true);
    wp_register_script('jvcf7p_validation', plugins_url('apimarket/public/js/jvcf7p_validation.js'), '', $GLOBALS['jvcf7p_current_version'], true);
    $scriptData = jvcf7p_get_data_for_client_script();
    wp_localize_script( 'jvcf7p_validation', 'scriptData', $scriptData );
    wp_enqueue_script( 'jvcf7p_validation' );
}

function jvcf7p_get_data_for_client_script(){
    // GET UPDATED USER OPTIONS

    /* GET ERROR MESSAGES */
    $errorMgs 			= $GLOBALS['jvcf7p_default_error_msgs'];
    $errorMsgRevised 	= array();
    foreach ($errorMgs as $key => $value) {
        $errorMsgRevised[str_replace('jvcf7p_msg_','',$key)] = $value;
    }

    /* GET REGEX */
    $customRegexValidations = get_posts('post_type=jvcf7p-custom&posts_per_page=50&meta_key=jvcf7p_validation_type&meta_value=custom_regex');

    $customRegex = array();
    foreach ($customRegexValidations as $key => $post):
        $postMetaData 			= get_post_meta($post->ID);
        $customRegex[$post->ID] = array(
            'id' 		=> $post->ID,
            'regex'		=> $postMetaData['jvcf7p_regex'][0],
            'error_mg'	=> $postMetaData['jvcf7p_error_message'][0]
        );
    endforeach;

    $scriptData = array(
        'jvcf7p_default_settings' 		=> $GLOBALS['jvcf7p_default_settings'],
        'jvcf7p_default_error_msgs'  	=> $errorMsgRevised,
        'jvcf7p_ajax_url'             => admin_url("admin-ajax.php?action=jvcf7p_ajax_validation"),
        'jvcf7p_regexs'					      => $customRegex
    );
    return $scriptData;
}

function apimarket_remote_call_post($url, $parameters) {
    global $wp_version;
    $url = $url . '?' . http_build_query($parameters);
    $args = array(
        'timeout' => 5,
        'redirection' => 5,
        'httpversion' => '2',
        'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url(),
        'blocking' => true,
        'headers' => get_option('cf7_to_api_default_header'),
        'cookies' => array(),
        'body' => null,
        'compress' => false,
        'decompress' => true,
        'sslverify' => true,
        'stream' => false,
        'filename' => null
    );
    return apimarket_remote_post($url, $args);
}

function jvcf7p_ajax_validation(){
    if(!session_id()) {session_start();}
    $method     = $_REQUEST['method'];
    if (isset($_REQUEST['fieldname'])){
        $fieldname  = $_REQUEST['fieldname'];
        $value      = sanitize_text_field(trim($_REQUEST[$fieldname]));
    }
    if (!wpcf7_verify_nonce($_REQUEST['_wpnonce'])) {
        $return = "No tienes permisos para usar este servicio";
        echo json_encode($return);
        wp_die();
    }
    switch ($method) {
        case 'checkUsername':
            if (username_exists($value)){
                $return = 'true';
            } else {
                $return = $GLOBALS['jvcf7p_default_error_msgs']['jvcf7p_msg_check_username'];
            }
            break;
        case 'customCode':
            $validationData 	=  get_post_meta($_REQUEST['custom_validation_id']);
            $customCodes      =  strtolower($validationData['jvcf7p_custom_codes'][0]);
            $customCodesArray =  json_decode($customCodes,true);
            if (in_array(strtolower($value), $customCodesArray)){
                $return = 'true';
            } else {
                $return = $validationData['jvcf7p_error_message'][0];
            }
            break;
        case 'sendVerificationCode':
            global $wp_session;
            $randomCode = rand(1000,9999);
            $email      = $_REQUEST['email'];
            $subject    = $GLOBALS['jvcf7p_default_settings']['jvcf7p_email_verify_subject'];
            $message    = str_replace('[CODE]', $randomCode, $GLOBALS['jvcf7p_default_settings']['jvcf7p_email_verify_body']);
            wp_mail($email, $subject, $message);
            $_SESSION['emailVerification'][$email]  = $randomCode;
            $return = 'success'; //.$randomCode;
            break;
        case 'verifyEmailCode':
            $email              = $_REQUEST['email'];
            $verificationCode   = $_SESSION['emailVerification'][$email];
            if (trim($value) == trim($verificationCode)){
                $return = 'true';
            } else {
                $return = $GLOBALS['jvcf7p_default_error_msgs']['jvcf7p_msg_email_verification'];
            }
            break;
        case 'checkCURP':
            try {
                $json = apimarket_remote_call_post("https://apimarket.mx/api/renapo/grupo/valida-curp", ['curp' => $value]);
                $return = ($json != null && isset($json['data']) && $json['data']['curp'] == $value) ? true : $GLOBALS['jvcf7p_default_error_msgs']['jvcf7p_msg_curp'];
            } catch(Exception $exception) {
                $return = $GLOBALS['jvcf7p_default_error_msgs']['jvcf7p_msg_curp'];
            }
            break;
        default:
            return true;
    }
    echo json_encode($return);
    wp_die();
}

function jvcf7p_wpcf7_mail_sent( $contact_form ) {
    $submission = WPCF7_Submission::get_instance();
    if ( $submission ) {
        $posted_data = $submission->get_posted_data();

        $customCodeValidations = get_posts('post_type=jvcf7p-custom&posts_per_page=50&meta_key=jvcf7p_allow_once&meta_value=yes');

        if (!empty($customCodeValidations)){
            $oneTimeCodeFields = array();
            foreach ($customCodeValidations as $key => $post):
                $fieldname                      = get_post_meta($post->ID, 'jvcf7p_field_name', true);
                $oneTimeCodeFields[$fieldname]  = $post->ID;
            endforeach;

            if (!empty($oneTimeCodeFields)){
                $matchedFields = array_intersect_key($posted_data,$oneTimeCodeFields);
                if (!empty($matchedFields)){
                    foreach ($matchedFields as $fieldname => $data):
                        $postID           =  $oneTimeCodeFields[$fieldname];
                        $customCodes      =  get_post_meta($postID, 'jvcf7p_custom_codes', true);
                        $customCodesArray =  json_decode($customCodes,true);
                        if (false !== $foundKey = array_search(strtolower($data), array_map('strtolower', $customCodesArray))){
                            unset($customCodesArray[$foundKey]);
                            update_post_meta($postID, 'jvcf7p_custom_codes', json_encode($customCodesArray));
                        }
                    endforeach;
                }
            }
        }
    }
};

function jvcf7p_register_post_type(){
    $args = array(
        'labels'             => array(),
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => false,
        'show_in_menu'       => false,
        'query_var'          => false,
        'rewrite'            => array( 'slug' => 'jvcf7-custom' ),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title')
    );
    register_post_type( 'jvcf7p-custom', $args );
}

function jvcf7_save_custom_validation(){
    $jvcf7p_custom_codes 		= $_POST['jvcf7p_custom_codes'];
    $jvcf7p_custom_code_array 	= explode("\n", $jvcf7p_custom_codes);
    $jvcf7p_custom_code_array	= array_map('trim', $jvcf7p_custom_code_array);
    $jvcf7p_custom_code_array   = array_filter($jvcf7p_custom_code_array);
    $jvcf7p_custom_code_json	= json_encode($jvcf7p_custom_code_array);

    $postData = array(
        'ID'			=> $_POST['jvcf7p_post_id'],
        'post_title'    => wp_strip_all_tags( $_POST['jvcf7p_validation_class'] ),
        'post_author'   => get_current_user_id(),
        'meta_input'	=> array(
            'jvcf7p_reference_name'  		=> $_POST['jvcf7p_reference_name'],
            'jvcf7p_validation_type' 		=> $_POST['jvcf7p_validation_type'],
            'jvcf7p_allow_once' 			=> $_POST['jvcf7p_allow_once'],
            'jvcf7p_error_message' 			=> $_POST['jvcf7p_error_message'],
            'jvcf7p_regex'					=> $_POST['jvcf7p_regex'],
            'jvcf7p_custom_codes'			=> $jvcf7p_custom_code_json,
            'jvcf7p_field_name'				=> $_POST['jvcf7p_field_name']
        ),
        'post_status'   => 'publish',
        'post_type'	  	=> 'jvcf7p-custom'
    );

    wp_insert_post( $postData );

    $return['status']   = 'ok';
    $return['body'] 	= 'Successfully Saved';
    return $return;
}

function jvcf7_delete_custom_validation($id){
    wp_delete_post($id,true);
    $return['status']   = 'ok';
    $return['body'] 	= 'Successfully Deleted';
    return $return;
}

function jvcf7_delete_old_custom_code(){
    delete_option('jvcf7p_custom_codes');
    $return['status']   = 'ok';
    $return['body'] 	= 'Old Custom Code Deleted';
    return $return;
}

function apimarket_shortcode_modal() {
    wp_enqueue_style('my-modal-style', plugins_url('apimarket/public/css/cf7-to-any-api-modal.css'));
    wp_enqueue_script('my-modal-script', plugins_url('apimarket/public/js/cf7-to-any-api-modal.js'), array('jquery'), null, true);

    wp_localize_script('my-modal-script', 'apimarket_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('apimarket_ajax_nonce')
    ));
    ob_start();
    ?>
    <div id="apimarket-modal" class="apimarket-modal">
        <div class="apimarket-modal-content">
            <span class="close-modal">&times;</span>
            <h2>Vista Previa <span id="apimarket-modal-spinner" class="wpcf7-spinner"></span> </h2>
            <table id="apimarket-pretty-table"></table>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode('apimarket_preview_modal', 'apimarket_shortcode_modal');

/**
 * To get all the values of the fields in the form, you can use the expression:
 * jet_fb_request_handler()->get_request() or $context->get_request()
 *
 * If the field is located in the middle of the repeater, then only
 * jet_fb_request_handler()->get_request(), but $context->get_request()
 * will return the values of all fields of the current repeater element
 *
 * @param $value mixed
 * @param $context \Jet_Form_Builder\Request\Parser_Context
 *
 * @return bool
 */
function jet_fb_v_apimarket_verify_curp( $value, $context ): bool {
    try {
        $json = apimarket_remote_call_post("https://apimarket.mx/api/sat/grupo/obtener-rfc", ['curp' => $value]);
        return ($json != null && isset($json['data']) && $json['data']['rfc'] == $value);
    } catch (Exception $exception) {
        return false;
    }
}

function jet_fb_v_apimarket_verify_rfc( $value, $context ): bool {
    try {
        $fields = $context->get_request();
        $json = apimarket_remote_call_post("https://apimarket.mx/api/renapo/grupo/obtener-rfc", ['curp' => $fields->curp]);
        return ($json != null && isset($json['data']) && $json['data']['rfc'] == $value);
    } catch (Exception $exception) {
        return false;
    }
}

function apimarket_ajax_handler() {
    check_ajax_referer('apimarket_ajax_nonce', 'nonce');
    try {
        parse_str($_POST['form'], $form_data);
        if (WPCF7_RECAPTCHA::get_instance()->verify($form_data['_wpcf7_recaptcha_response']))
            wp_send_json_error(['error' => "You're bot."]);
        $wpcf7_id = (int) $form_data['_wpcf7'];
        $cf7anyapi_options =  Cf7_To_Any_Api_Admin::create_request($wpcf7_id, $form_data);
        if ($cf7anyapi_options['body'] == "\"\"")
            wp_send_json_error(['error' => "The request doesn't have valid data."]);
        $request =  json_decode($cf7anyapi_options['body']);
        $response = apimarket_remote_call_post($cf7anyapi_options['cf7anyapi_base_url'], $request)['data'];
        $keys = array_keys($response);
        $visible_keys = array_slice($keys, 0, 3);
        foreach ($response as $key => $value) {
            if (!in_array($key, $visible_keys)) {
                $response[$key] = "Disponible hasta recepción del correo.";
            }
        }
        wp_send_json_success($response);
    } catch (Exception $exception) {
        wp_send_json_error(['error' => $exception->getMessage()]);
    }
    wp_die();
}

add_action('wp_ajax_nopriv_apimarket_ajax_handler', 'apimarket_ajax_handler');
add_action('wp_ajax_apimarket_ajax_handler', 'apimarket_ajax_handler');