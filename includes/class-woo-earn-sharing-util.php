<?php
class Woo_Earn_Sharing_Util {
	/**
	 * Util functions.
	 *
	 * @since    1.0.0
	 */
	public function get_user_balance() {
    if (!is_user_logged_in())
      return;
    return get_user_meta(get_current_user_id(),'wooes_balance',true);
	}

  public function get_user_code(){
    if (!is_user_logged_in())
      return;
    return get_user_meta(get_current_user_id(),'wooes_code',true);
  }
  public function get_user_by_code($code){
    $users = get_users(array(
      'meta_key'     => 'wooes_code',
      'meta_value'   => (string)$code,
      'meta_compare' => '=',
    ));
    return $users;
  }

  public function reward_user_by_code($code,$order_id){
    $user = $this->get_user_by_code($code)[0];

    if (!empty($user)){
      $order = new WC_Order($order_id);
      $old_balance = floatval(get_user_meta($user->ID, 'wooes_balance',true));
      $reward_percent = floatval(get_option('wooes_reward_percent'))/100;
      update_user_meta($user->ID, 'wooes_balance', $old_balance+$order->get_total()*$reward_percent);
      wp_die(get_user_meta($user->ID, 'wooes_balance',true));

    }
  }

  public function wooes_get_fee($total){
    $balance = $this->get_user_balance();
    $discount = null;
		if (floatval($total) <= $balance){
			$discount = $total-1;
		}else{
			$discount = $balance;
		}
    return $discount;
  }

  function give_back_money($user_id, $money){

    $money = (double) $money;

    $balance = (double) get_user_meta($user_id, 'wooes_balance', true);

    update_user_meta($user_id, 'wooes_balance', $money + $balance );
  }

	public function generate_new_user_code(){
			$length = absint(get_option('wooes_code_length',6));
			if (get_option('wooes_code_alphanumeric') === 'true'){
				$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			}else{
				$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			}
			$charactersLength = strlen($characters);
			$randomString = '';
			do {
				for ($i = 0; $i < $length; $i++) {
						$randomString .= $characters[rand(0, $charactersLength - 1)];
				}
				$users = get_users(array(
					'meta_key'     => 'wooes_code',
					'meta_value'   => $randomString,
					'meta_compare' => '=',
				));
			} while (sizeof($users) > 0);

			return $randomString;
	}

}
 ?>
