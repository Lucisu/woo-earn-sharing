<?php

/**
 * Fired during plugin activation
 *
 * @link       https://luciusdesenvolvimento.com
 * @since      1.0.0
 *
 * @package    Woo_Earn_Sharing
 * @subpackage Woo_Earn_Sharing/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Woo_Earn_Sharing
 * @subpackage Woo_Earn_Sharing/includes
 * @author     Lucius Desenvolvimento <contato@luciusdesenvolvimento.com>
 */
class Woo_Earn_Sharing_Activator {

	/**
	 *
	 * Plugin Activation
	 * Resave Permalinks
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		if ( ! get_option( 'wooes_flush_rewrite_rules_flag' ) ) {
        add_option( 'wooes_flush_rewrite_rules_flag', true );
    }
	}

}
