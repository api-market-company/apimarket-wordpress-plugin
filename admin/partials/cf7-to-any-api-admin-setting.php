<?php

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
$cf7anyapi_options = $cf7anyapi_object->setting_get_options();

?>

    <style>
        .flex-container {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .flex-row > div {
            flex: 1;
        }
    </style>

    <div class="wrap">
        <h1><?php echo esc_html('ApiMarket Settings'); ?></h1>
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save_cf7_to_any_api_update_settings"/>
            <?php wp_nonce_field(-1, 'save_cf7_to_any_api_update_settings'); ?>
            <?php if (isset($_GET["update-status"])): ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php echo esc_html('Settings save successfully!'); ?>.</p>
                </div>
            <?php endif; ?>
            <div class="cf7_to_any_api_setting_form flex-container">
                <div>
                    <h2>Would you like to make the API calls before the mail is sent?</h2>
                    <input type="checkbox" name="cf7_to_api_before_mail_sent" id="cf7_to_api_before_mail_sent" value="1"
                        <?php if ($cf7anyapi_options['cf7_to_api_before_mail_sent']) echo 'checked="checked"'; ?>>
                    <label for="cf7_to_api_before_mail_sent"><?php echo esc_html('Enable this option means the plugin will fire API call after form submit CF7 Hook wpcf7_before_send_mail'); ?></label>
                </div>

                <div>
                    <h2>Would you like to save API responses in entries of your forms?</h2>
                    <input type="checkbox" name="cf7_to_api_save_api_response_in_entries" id="cf7_to_api_save_api_response_in_entries" value="1"
                        <?php if ($cf7anyapi_options['cf7_to_api_save_api_response_in_entries']) echo 'checked="checked"'; ?>>
                    <label for="cf7_to_api_save_api_response_in_entries"><?php echo esc_html('Enable this option means the plugin will save the user responses with API responses.'); ?></label>
                </div>

                <div class="flex-container">
                    <h2>What are your default headers?</h2>
                    <textarea name="cf7_to_api_default_header" id="cf7_to_api_default_header"
                              rows="4"><?php echo (isset($cf7anyapi_options['cf7_to_api_default_header'])  and $cf7anyapi_options['cf7_to_api_default_header'] != "") ? esc_textarea($cf7anyapi_options['cf7_to_api_default_header']) : 'Authorization: Bearer xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
Content-Type: application/json
Accept: application/json'; ?></textarea>
                    <label for="cf7_to_api_default_header"><?php echo esc_html('Enabling this option means the plugin will call this header if you\'ve not set it before.'); ?></label>
                </div>
                <p class="submit">
                    <input type="submit" name="Submit" class="button-primary"
                           value="<?php echo esc_attr('Save Changes') ?>"/>
                </p>
            </div>
        </form>
    </div>