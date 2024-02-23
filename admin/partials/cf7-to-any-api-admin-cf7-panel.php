<?php
global $apimarket_services;

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.itpathsolutions.com/
 * @since      1.0.0
 *
 * @package    Cf7_To_Any_Api
 * @subpackage Cf7_To_Any_Api/admin/partials
 */
$cf7anyapi_object = new Cf7_To_Any_Api();
$cf7anyapi_options = $cf7anyapi_object->Cf7_To_Any_Api_get_options($cf7anyapi_object->find_apimarket_service_from_cf7($_POST['contact_form7_form_id']));

$cf7anyapi_id = empty($cf7anyapi_options['cf7anyapi_id']) ? '' : $cf7anyapi_options['cf7anyapi_id'];
$cf7anyapi_title = empty($cf7anyapi_options['cf7anyapi_title']) ? '' : $cf7anyapi_options['cf7anyapi_title'];
$cf7anyapi_base_url = (empty($cf7anyapi_options['cf7anyapi_base_url']) ? 'https://apimarket.mx/api/renapo/grupo/valida-curp' : $cf7anyapi_options['cf7anyapi_base_url']);
$cf7anyapi_input_type = (empty($cf7anyapi_options['cf7anyapi_input_type']) ? 'params' : $cf7anyapi_options['cf7anyapi_input_type']);
$cf7anyapi_method = (empty($cf7anyapi_options['cf7anyapi_method']) ? 'POST' : $cf7anyapi_options['cf7anyapi_method']);
$cf7anyapi_header_request = (empty($cf7anyapi_options['cf7anyapi_header_request']) ? get_option('cf7_to_api_default_header', '') : $cf7anyapi_options['cf7anyapi_header_request']);

$cf7anyapi_json_format = (empty($cf7anyapi_options['cf7anyapi_json_format']) ? '' : $cf7anyapi_options['cf7anyapi_json_format']);

?>

<input type="hidden" name="cf7anyapi_id" value="<?php echo $cf7anyapi_id; ?>">
<input type="hidden" name="cf7anyapi_input_type" value="<?php echo $cf7anyapi_input_type; ?>">
<input type="hidden" name="cf7anyapi_method" value="<?php echo $cf7anyapi_method; ?>">
<input type="hidden" name="cf7anyapi_header_request" value="<?php echo $cf7anyapi_header_request; ?>">
<input type="hidden" name="cf7anyapi_title" value="<?php echo $cf7anyapi_title; ?>">

<div class="cf7anyapi_field">
    <?php wp_nonce_field('cf7_to_any_api_cpt_nonce','cf7_to_any_api_cpt_nonce' ); ?>
    <label for="cf7anyapi_base_url"><?php esc_html_e( 'ApiMarket services', 'contact-form-to-any-api' ); ?> </label>
    <input  list="list_apimarket_services" type="text" id="cf7anyapi_base_url" name="cf7anyapi_base_url" placeholder="<?php esc_attr_e("https://apimarket.mx/api/renapo/grupo/valida-curp"); ?>" value="<?php echo esc_url($cf7anyapi_base_url); ?>" placeholder="<?php esc_attr_e( 'Enter Your API URL', 'contact-form-to-any-api' ); ?>" required>
    <datalist id="list_apimarket_services">
        <?php
        foreach ($apimarket_services as $url => $option) {
            $selected = $cf7anyapi_base_url == $url || $cf7anyapi_base_url == '' ? ' selected="selected"' : '';
            echo '<option value="' . esc_attr($url) . '"' . $selected . '>' . esc_html__($option['label'], 'contact-form-to-any-api') . '</option>';
        }
        ?>
    </datalist>
</div>