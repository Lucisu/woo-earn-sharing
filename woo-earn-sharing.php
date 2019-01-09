<?php

/**
 * @link              https://luciusdesenvolvimento.com
 * @since             1.0.0
 * @package           Woo_Earn_Sharing
 *
 * @wordpress-plugin
 * Plugin Name:       Woo Earn Sharing
 * Plugin URI:        https://luciusdesenvolvimento.com/Woo-Earn-Sharing
 * Description:       Let your users share their own codes to earn discounts.
 * Version:           1.0.0
 * Author:            Lucius Desenvolvimento
 * Author URI:        https://luciusdesenvolvimento.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-earn-sharing
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define( 'WOOES_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woo-earn-sharing-activator.php
 */
function activate_woo_earn_sharing() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-earn-sharing-activator.php';
	Woo_Earn_Sharing_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woo-earn-sharing-deactivator.php
 */
function deactivate_woo_earn_sharing() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-earn-sharing-deactivator.php';
	Woo_Earn_Sharing_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woo_earn_sharing' );
register_deactivation_hook( __FILE__, 'deactivate_woo_earn_sharing' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woo-earn-sharing.php';

/**
 * Begins execution of the plugin.
 *
 *
 * @since    1.0.0
 */
function run_woo_earn_sharing() {

	$plugin = new Woo_Earn_Sharing();
	$plugin->run();

}
run_woo_earn_sharing();
