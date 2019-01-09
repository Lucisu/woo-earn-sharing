<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://luciusdesenvolvimento.com
 * @since      1.0.0
 *
 * @package    Woo_Earn_Sharing
 * @subpackage Woo_Earn_Sharing/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woo_Earn_Sharing
 * @subpackage Woo_Earn_Sharing/public
 * @author     Lucius Desenvolvimento <contato@luciusdesenvolvimento.com>
 */
class Woo_Earn_Sharing_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woo-earn-sharing-util.php';
		$this->util = new Woo_Earn_Sharing_Util();

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-earn-sharing-public.css', array(), $this->version, 'all' );

	}


	// ------------------
	// 1. Register new endpoint to use for My Account page
	// Note: Resave Permalinks or it will give 404 error

	function wooes_tab_support_endpoint() {
	    add_rewrite_endpoint( 'my-referrals', EP_ROOT | EP_PAGES );
	}

	// ------------------
	// 2. Add new query var

	function wooes_tab_query_vars( $vars ) {
	    $vars[] = 'my-referrals';
	    return $vars;
	}

	// ------------------
	// 3. Insert the new endpoint into the My Account menu

	function wooes_add_my_account_tab( $items ) {
		// Remove the logout menu item.
			$logout = $items['customer-logout'];
			unset( $items['customer-logout'] );
			$items['my-referrals'] = empty((string)get_option('wooes_referrals')) ? __('Referrals','woo-earn-sharing') : (string)get_option('wooes_referrals');
			$items['customer-logout'] = $logout;
			return $items;
	}

	function wooes_my_account_content() {
		ob_start();
		require_once plugin_dir_path( __FILE__ ) . 'partials/woo-earn-sharing-public-display.php';
		$output = ob_get_clean();
		echo $output;
	}



	function wooes_registration_new_user( $user_id ) {
			update_user_meta($user_id, 'wooes_code', $this->util->generate_new_user_code());
      update_user_meta($user_id, 'wooes_balance', 0);
	}
	function wooes_woocommerce_order_status_completed( $order_id ) {
	    $code = sanitize_text_field(get_post_meta($order_id,'wooes_referral_code',true));
			if (!empty($code)){
				$this->util->reward_user_by_code($code,$order_id);
			}
	}
	function wooes_woocommerce_order_status_cancelled( $order_id ) {
	    $wooes_discount = (double) sanitize_text_field(get_post_meta($order_id,'wooes_discount',true));
			$order = new WC_Order( $order_id );
			$user_id = $order->get_user_id();
			if (!empty($wooes_discount)){
				if ((int) get_post_meta($order_id,'wooes_money_have_back',true) === 0 && get_option('wooes_money_back') === 'true') {
					$this->util->give_back_money($user_id,$wooes_discount);
					update_post_meta($order_id, 'wooes_money_have_back', 1);

				}
			}
	}


	function wooes_checkout_field($checkout)
	{
		echo '<div id="wooes_checkout_field"><h2>' . __('Friend Code','woo-earn-sharing') . '</h2>';
		woocommerce_form_field('wooes_checkout_field', array(
			'type' => 'text',
			'class' => array(
				'wooes-checkout-field form-row-wide'
			) ,
			'label' => __('Add here a friend code, if have one', 'woo-earn-sharing') ,
			'placeholder' => __('XXXXXXXX','woo-earn-sharing') ,
			'required' => false,
		));
		echo '</div>';
	}

	function wooes_checkout_field_process()
	{
		if ($_POST['wooes_checkout_field']){
			$code = sanitize_text_field(str_replace(' ', '', $_POST['wooes_checkout_field']));
			if (sizeof($this->util->get_user_by_code($code)) < 1){
				wc_add_notice(__('Invalid Friend Code.','woo-earn-sharing') , 'error');
			}
			if ($this->util->get_user_code() === $code) {
				wc_add_notice(__('You cannot use your code as Friend Code.','woo-earn-sharing') , 'error');
			}
		}
	}

	function wooes_checkout_field_update_order_meta($order_id)
	{
		if (!empty($_POST['wooes_checkout_field'])) {
			update_post_meta($order_id, 'wooes_referral_code', sanitize_text_field(str_replace(' ', '', $_POST['wooes_checkout_field'])));
		}
	}

	function wooes_discount_balance( $cart ) {

	    $total = $cart->cart_contents_total;
			$discount = $this->util->wooes_get_fee($total);
			if (!empty($discount)){
				$cart->add_fee( __('Balance', 'woo-earn-sharing'), - $discount );
			}
	}

	function wooes_checkout_add_meta( $order_id ) {
		$order = new WC_Order( $order_id );
		$user_id = $order->get_user_id();
		$subtotal = $subtotal_taxes = 0;

		foreach( $order->get_items() as $item ){
				$subtotal += (double) $item->get_subtotal();
				$subtotal_taxes += (double) $item->get_subtotal_tax();
		}
		$total = $subtotal + $subtotal_taxes;


		$discount = $this->util->wooes_get_fee($total);
		$balance = $this->util->get_user_balance();

		if (!empty($discount)){
			update_user_meta($order->get_user_id(), 'wooes_balance', $balance - $discount);
			update_post_meta($order_id, 'wooes_discount', $discount);
			update_post_meta($order_id, 'wooes_money_have_back', 0);
		}
	}

	function wooes_user_balance_function( $atts ){
		return wc_price($this->util->get_user_balance());
	}

	function wooes_user_code_function( $atts ){
		return $this->util->get_user_code();
	}
	function wooes_flush_rewrite_rules_maybe() {
    if ( get_option( 'wooes_flush_rewrite_rules_flag' ) ) {
        flush_rewrite_rules();
        delete_option( 'wooes_flush_rewrite_rules_flag' );
    }
	}
}
