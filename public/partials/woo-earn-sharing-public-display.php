<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://luciusdesenvolvimento.com
 * @since      1.0.0
 *
 * @package    Woo_Earn_Sharing
 * @subpackage Woo_Earn_Sharing/public/partials
 */

require_once plugin_dir_path( dirname( __FILE__ ) ) . '../includes/class-woo-earn-sharing-util.php';

$util = new Woo_Earn_Sharing_Util();
$balance = $util->get_user_balance();
$code = $util->get_user_code();
?>
<div class="wooes-my-account">

  <h3 class="wooes-referrals-title"><?php echo empty((string)get_option('wooes_referrals')) ? __('Referrals','woo-earn-sharing') : (string)get_option('wooes_referrals'); ?></h3>

  <div class="wooes-my-account-page">
    <?php echo do_shortcode(wp_kses_post(get_option('wooes_page_html'))) ?>
  </div>
</div>
