<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://luciusdesenvolvimento.com
 * @since      1.0.0
 *
 * @package    Woo_Earn_Sharing
 * @subpackage Woo_Earn_Sharing/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Woo_Earn_Sharing
 * @subpackage Woo_Earn_Sharing/includes
 * @author     Lucius Desenvolvimento <contato@luciusdesenvolvimento.com>
 */
class Woo_Earn_Sharing {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Woo_Earn_Sharing_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WOOES_VERSION' ) ) {
			$this->version = WOOES_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'woo-earn-sharing';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Woo_Earn_Sharing_Loader. Orchestrates the hooks of the plugin.
	 * - Woo_Earn_Sharing_i18n. Defines internationalization functionality.
	 * - Woo_Earn_Sharing_Admin. Defines all hooks for the admin area.
	 * - Woo_Earn_Sharing_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woo-earn-sharing-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woo-earn-sharing-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woo-earn-sharing-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-woo-earn-sharing-public.php';

		$this->loader = new Woo_Earn_Sharing_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Woo_Earn_Sharing_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Woo_Earn_Sharing_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Woo_Earn_Sharing_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'wooes_create_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'wooes_register_plugin_settings' );
		$this->loader->add_filter( 'manage_users_custom_column', $plugin_admin, 'add_user_column_data', 10, 3 );
		$this->loader->add_filter( 'manage_users_columns', $plugin_admin, 'add_user_columns');

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Woo_Earn_Sharing_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'init', $plugin_public, 'wooes_tab_support_endpoint' );
		$this->loader->add_action( 'query_vars', $plugin_public, 'wooes_tab_query_vars', 0);
		$this->loader->add_filter( 'woocommerce_account_menu_items', $plugin_public, 'wooes_add_my_account_tab' );
		$this->loader->add_action( 'woocommerce_account_my-referrals_endpoint', $plugin_public, 'wooes_my_account_content' );
		$this->loader->add_action( 'user_register', $plugin_public, 'wooes_registration_new_user',10,1 );
		$this->loader->add_action( 'woocommerce_after_order_notes', $plugin_public, 'wooes_checkout_field' );
		$this->loader->add_action( 'woocommerce_checkout_process', $plugin_public, 'wooes_checkout_field_process' );
		$this->loader->add_action( 'woocommerce_checkout_update_order_meta', $plugin_public, 'wooes_checkout_field_update_order_meta' );
		$this->loader->add_action( 'woocommerce_order_status_completed', $plugin_public, 'wooes_woocommerce_order_status_completed' );
		$this->loader->add_action( 'woocommerce_order_status_cancelled', $plugin_public, 'wooes_woocommerce_order_status_cancelled' );
		$this->loader->add_action( 'woocommerce_order_status_failed', $plugin_public, 'wooes_woocommerce_order_status_cancelled' );
		$this->loader->add_action( 'woocommerce_order_status_refunded', $plugin_public, 'wooes_woocommerce_order_status_cancelled' );
		$this->loader->add_action( 'woocommerce_cart_calculate_fees', $plugin_public, 'wooes_discount_balance',25,1 );
		$this->loader->add_action( 'woocommerce_thankyou', $plugin_public, 'wooes_checkout_add_meta' );
		$this->loader->add_shortcode( 'wooes_user_balance', $plugin_public, 'wooes_user_balance_function' );
		$this->loader->add_shortcode( 'wooes_user_code', $plugin_public, 'wooes_user_code_function' );
		$this->loader->add_action( 'init', $plugin_public, 'wooes_flush_rewrite_rules_maybe', 20 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Woo_Earn_Sharing_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
