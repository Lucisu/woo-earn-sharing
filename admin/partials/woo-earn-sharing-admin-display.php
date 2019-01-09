<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://luciusdesenvolvimento.com
 * @since      1.0.0
 *
 * @package    Woo_Earn_Sharing
 * @subpackage Woo_Earn_Sharing/admin/partials
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
<h1>Wooes</h1>

<form method="post" action="options.php">
    <?php settings_fields( 'wooes-plugins-settings-group' ); ?>
    <?php do_settings_sections( 'wooes-plugins-settings-group' ); ?>
    <table class="form-table">

        <tr valign="top">
        <th scope="row"><?php  _e('Reward %','woo-earn-sharing') ?></th>
        <td><input type="number" step="0.01" name="wooes_reward_percent" value="<?php echo esc_attr( get_option('wooes_reward_percent') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e('My Account Tab Label', 'woo-earn-sharing') ?></th>
        <td><input type="text" placeholder="Referrals" name="wooes_referrals" value="<?php echo esc_attr( get_option('wooes_referrals') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e('User code length','woo-earn-sharing') ?></th>
        <td><input type="number" step="1" name="wooes_code_length" value="<?php echo esc_attr( get_option('wooes_code_length',6) ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e('User code Alphanumeric','woo-earn-sharing') ?></th>
        <td><input type="checkbox" name="woolim_require_license_on_register" value="true" <?php checked('true', get_option('woolim_require_license_on_register')); ?> ></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e('Give credit back to user, when order change to Refunded, Cancelled or Failed', 'woo-earn-sharing') ?></th>
        <td><input type="checkbox" name="wooes_money_back" value="true" <?php checked('true', get_option('wooes_money_back')); ?> ></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e('Referrals page HTML','woo-earn-sharing') ?>
          <p>
            [wooes_user_balance]
            [wooes_user_code]
          </p>
        </th>
        <td>
        <?php
        $settings = array('teeny' => true,'textarea_rows' => 10,'tabindex' => 1);
        wp_editor(wp_kses_post( get_option('wooes_page_html')), 'wooes_page_html', $settings);
        ?>
       </td>
       </tr>

       <tr valign="top">
       <td>
         <p id="wooes-regenerate-codes"><?php _e('Regenerate All Users Codes', 'woo-earn-sharing') ?></p>
       </td>
       </tr>
    </table>

    <?php submit_button(); ?>


<div style="text-align:right;">
  <?php _e('Like the plugin? Please, consider pay me a coffe! :)','woo-earn-sharing') ?>
  <br>
  <br>
  <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="7N3GUHEY8GT6E">
    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
    <img alt="" border="0" src="https://www.paypalobjects.com/pt_BR/i/scr/pixel.gif" width="1" height="1">
  </form>

</div>



</form>
</div>
