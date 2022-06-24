<?php

namespace ITP;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

class EcomSystem
{
    public function __construct()
    {
        add_action('show_user_profile', [$this, 'html_store_meta']);
        add_action('edit_user_profile', [$this, 'html_store_meta']);
        add_action('personal_options_update', [$this, 'update_store_meta']);
        add_action('edit_user_profile_update', [$this, 'update_store_meta']);
    }
    public function html_store_meta($user)
    {

        $has_shop = false;
        $shop_meta = get_user_meta($user->ID, 'has_shop', false);
        if (!empty($shop_meta)) {
            $has_shop = get_user_meta($user->ID, 'has_shop', true);
        }



?>

<h3>Store Owner</h3>
<div class="itprix_radio">
    <div class="radio_field">
        <input type="radio" id="radio-one" name="has_shop" value="no" checked <?php checked($has_shop, 'no'); ?> />
        <label for="radio-one">No</label>
        <input type="radio" id="radio-two" name="has_shop" value="yes" <?php checked($has_shop, 'yes'); ?> />
        <label for="radio-two">Yes</label>
    </div>
</div>
<?php

    }
    public function update_store_meta($user_id)
    {
        global $wpdb;
        $users = new \WP_User(1);
        if (!current_user_can('edit_user', get_current_user_id())) {
            return false;
        }
        if (isset($_POST['has_shop'])) {
            update_user_meta($user_id, 'has_shop', sanitize_text_field(wp_unslash($_POST['has_shop'])));
        }
    }
}
