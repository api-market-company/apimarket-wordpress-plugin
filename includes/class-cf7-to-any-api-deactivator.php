<?php

class Cf7_To_Any_Api_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        
        if(wp_verify_nonce($_REQUEST['cf7_any_api_nonce_field'], 'cf7_any_api_action') && isset($_POST) && !empty($_POST['selected-reason'])){

            self::send_data_to_google_form($_POST);

        }
	}
	/*
    * Plugin deactivate send data google form
    */
    public static function send_data_to_google_form($data){
        $googleFormsURL = 'https://docs.google.com/forms/d/e/1FAIpQLSdTu7j2GPyvGokqf44Zhqf4JE0PwnuJvTmBD9ZggeipAUMEJg/formResponse';
        $cf7email = sanitize_email($data['selected-email']);
        $feedback_answer = stripslashes(sanitize_text_field($data['comment']));
        $cf7siteurl = get_site_url();

        $data_array = array(
            'entry.4285023' => stripslashes($data['selected-reason']),
            'entry.1742235403' => $feedback_answer,
            'entry.1651427738' => $cf7email,
            'entry.1322912050' => $cf7siteurl
        );
        if($data['selected-reason'] == '__other_option__' ){
            $data_array['entry.4285023.other_option_response'] = "__other_option__";
        }
        $response = wp_remote_post($googleFormsURL, array(
            'body' => $data_array,
            'timeout' => 20,
            'sslverify' => true
        ));
        if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) != 200 ) {
            $error_message = is_wp_error($response) ? $response->get_error_message() : 'Request failed.';
        }
    }

}
