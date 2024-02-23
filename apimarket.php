<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://apimarket.mx/
 * @since             1.0.0
 * @package           apimarket
 *
 * @wordpress-plugin
 * Plugin Name:       ApiMarket
 * Plugin URI:        https://apimarket.mx/
 * Description:       Send CF7 Data to ApiMarket's endpoints.
 * Version:           3.0.0
 * Author:            ApiMarket
 * Author URI:        https://apimarket.mx/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       contact-form-to-api-market
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CF7_TO_ANY_API_VERSION', '1.7.1');

//Define curl url
define( 'CF7_CURL_DOMAIN', 'https://www.apimarket.mx' );

// Plugin Basename
define( 'CF7_TO_ANY_API_PLUGIN_BASENAME', basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cf7-to-any-api-activator.php
 */
function activate_apimarket() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-cf7-to-any-api-activator.php';
    Cf7_To_Any_Api_Activator::activate();
}

register_activation_hook( __FILE__, 'activate_apimarket' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cf7-to-any-api.php';

require plugin_dir_path(__FILE__) . 'includes/apimarket_services.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cf7_to_any_api() {

	$plugin = new Cf7_To_Any_Api();
	$plugin->run();

}
run_cf7_to_any_api();
