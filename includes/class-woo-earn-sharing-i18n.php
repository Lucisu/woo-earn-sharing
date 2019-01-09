<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://luciusdesenvolvimento.com
 * @since      1.0.0
 *
 * @package    Woo_Earn_Sharing
 * @subpackage Woo_Earn_Sharing/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Woo_Earn_Sharing
 * @subpackage Woo_Earn_Sharing/includes
 * @author     Lucius Desenvolvimento <contato@luciusdesenvolvimento.com>
 */
class Woo_Earn_Sharing_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woo-earn-sharing',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
