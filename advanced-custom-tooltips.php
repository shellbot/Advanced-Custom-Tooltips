<?php

/**
 * @link              http://codebyshellbot.com
 * @since             1.0.0
 * @package           Advanced_Custom_Tooltips
 *
 * @wordpress-plugin
 * Plugin Name:       Advanced Custom Tooltips
 * Plugin URI:        http://codebyshellbot.com/wordpress-plugins/advanced-custom-tooltips
 * Description:       Advanced tooltip creator
 * Version:           1.0.1
 * Author:            Shellbot
 * Author URI:        http://codebyshellbot.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       advanced-custom-tooltips
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-advanced-custom-tooltips-activator.php
 */
function activate_advanced_custom_tooltips() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-advanced-custom-tooltips-activator.php';
	Advanced_Custom_Tooltips_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-advanced-custom-tooltips-deactivator.php
 */
function deactivate_advanced_custom_tooltips() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-advanced-custom-tooltips-deactivator.php';
	Advanced_Custom_Tooltips_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_advanced_custom_tooltips' );
register_deactivation_hook( __FILE__, 'deactivate_advanced_custom_tooltips' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-advanced-custom-tooltips.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_advanced_custom_tooltips() {

	$plugin = new Advanced_Custom_Tooltips();
	$plugin->run();

}
run_advanced_custom_tooltips();
