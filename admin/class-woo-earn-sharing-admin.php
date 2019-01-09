<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://luciusdesenvolvimento.com
 * @since      1.0.0
 *
 * @package    Woo_Earn_Sharing
 * @subpackage Woo_Earn_Sharing/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Earn_Sharing
 * @subpackage Woo_Earn_Sharing/admin
 * @author     Lucius Desenvolvimento <contato@luciusdesenvolvimento.com>
 */
class Woo_Earn_Sharing_Admin {

	private $util;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woo-earn-sharing-util.php';

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->util = new Woo_Earn_Sharing_Util();

	}


	/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $this->version, 'all' );
	}
		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 */
	public function enqueue_scripts() {
		if (!empty($_GET["page"])){
			 if($_GET["page"] == "wooes-settings")
    	{
				wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), $this->version, false );

				$dataToBePassed = array(
			    'message'            => __('You are sure that want regenerate all user\'s code?','woo-earn-sharing')
				);
				wp_localize_script( $this->plugin_name, 'php_vars', $dataToBePassed );

			}
		}

	}
	function wooes_create_menu() {
		add_submenu_page('woocommerce','Wooes Settings', 'Wooes', 'manage_options','wooes-settings',array($this,'wooes_settings_page') );
	}


	function wooes_register_plugin_settings() {
		register_setting( 'wooes-plugins-settings-group', 'wooes_reward_percent', array('default' => 10) );
		register_setting( 'wooes-plugins-settings-group', 'wooes_referrals' );
		register_setting( 'wooes-plugins-settings-group', 'wooes_code_length', array('default' => 6) );
		register_setting( 'wooes-plugins-settings-group', 'wooes_code_alphanumeric', array('default' => 'false') );
		register_setting( 'wooes-plugins-settings-group', 'wooes_money_back', array('default' => 'true') );
		register_setting( 'wooes-plugins-settings-group', 'wooes_page_html', array('default' => '<div class="wooes-my-account-info"><div class="current-balance"><p>'.__('Balance','woo-earn-sharing').': <span>[wooes_user_balance]</span>   </p></div><div class="current-code"><p>'.__('Your Code','woo-earn-sharing').': <span>[wooes_user_code]</span> </p></div></div>') );
	}

	function wooes_settings_page() {
		if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			if (!empty($_GET['regenerate-codes'])) {
				echo "</br>";
				echo "</br>";
				_e('Done!', 'woo-earn-sharing');
				$users = get_users();
				foreach ($users as $key => $value) {
					$new_code = $this->util->generate_new_user_code();
					update_user_meta($value->data->ID, 'wooes_code', $new_code);
					echo "</br>";
					echo "User ID " . $value->data->ID;
					_e(' Setted to ','woo-earn-sharing');
					echo $new_code;
				}

				return;
			}
		}
		ob_start();
		include plugin_dir_path( __FILE__ ) . 'partials/woo-earn-sharing-admin-display.php';
		$output = ob_get_clean();
		echo $output;
	}

//add columns to User panel list page
function add_user_columns($column) {
    $column['wooes_code'] = 'Referral Code';
    return $column;
}
//add the data
function add_user_column_data( $val, $column_name, $user_id ) {
    $user = get_userdata($user_id);

    switch ($column_name) {
        case 'wooes_code' :
            return get_user_meta($user_id,'wooes_code',true);
            break;
        default:
    }
    return;
}


}
