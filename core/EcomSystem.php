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
        $first_name = get_user_meta($user->ID, 'first_name', true);
        $user_phone = get_user_meta($user->ID, 'user_phone', true);
        if (!empty($shop_meta)) {
            $has_shop = get_user_meta($user->ID, 'has_shop', true);
        }



?>
<h2>Customer Information</h2>
<table class="form-table itprix_meta_table" role="presentation">
    <tbody>
        <tr>
            <th>
                <label for="first_name">First Name</label>
            </th>
            <td>
                <input type="text" id="first_name" name="first_name" value="<?php echo $first_name; ?>" />
            </td>
        </tr>
        <tr>
            <th>
                <label for="user_phone">User Phone number</label>
            </th>
            <td>
                <input type="text" id="user_phone" name="user_phone" value="<?php echo $user_phone; ?>" />
            </td>
        </tr>

    </tbody>
</table>
<h2>Store Information</h2>
<table class="form-table itprix_meta_table" role="presentation">
    <tbody>

        <tr id="has_store">
            <th>
                <label for="has_store">Own Store</label>
            </th>
            <td>
                <div class="itprix_radio">
                    <div class="radio_field">
                        <input type="radio" id="radio-one" name="has_shop" value="no" checked
                            <?php checked($has_shop, 'no'); ?> />
                        <label for="radio-one">No</label>
                        <input type="radio" id="radio-two" name="has_shop" value="yes"
                            <?php checked($has_shop, 'yes'); ?> />
                        <label for="radio-two">Yes</label>
                    </div>
                </div>
            </td>
        </tr>

        <?php
                if ($has_shop == "yes") {
                    $store_name = get_user_meta($user->ID, 'store_name', true);
                    $store_phone = get_user_meta($user->ID, 'store_phone', true);


                ?>
        <tr id="store_name">
            <th>
                <label for="store_name">Store Name</label>
            </th>
            <td>
                <input type="text" id="store_name" name="store_name" value="<?php echo $store_name; ?>" />
            </td>

        </tr>
        <tr id="store_phone">
            <th>
                <label for="store_phone">Store Phone Number</label>
            </th>
            <td>
                <input type="tel" id="store_phone" name="store_phone" value="<?php echo $store_phone; ?>" />
            </td>

        </tr>
    </tbody>
</table>
<?php
                }
            }
            public function update_store_meta($user_id)
            {
                $has_shop = get_user_meta($user_id, 'has_shop', true);
                if (!current_user_can('edit_user', get_current_user_id())) {
                    return false;
                }
                if (isset($_POST['user_phone'])) {
                    update_user_meta($user_id, 'user_phone', sanitize_text_field(wp_unslash($_POST['user_phone'])));
                }
                if (isset($_POST['user_phone'])) {
                    update_user_meta($user_id, 'billing_phone', wc_clean($_POST['user_phone']));
                }
                if (isset($_POST['has_shop'])) {
                    update_user_meta($user_id, 'has_shop', sanitize_text_field(wp_unslash($_POST['has_shop'])));
                }
                if ($has_shop == "yes") {

                    $customer = new \WP_User($user_id);
                    $customer->add_role('shop_manager');
                    if (isset($_POST['store_name'])) {
                        update_user_meta($user_id, 'store_name', sanitize_text_field(wp_unslash($_POST['store_name'])));
                    }
                    if (isset($_POST['store_phone'])) {
                        update_user_meta($user_id, 'store_phone', sanitize_text_field(wp_unslash($_POST['store_phone'])));
                    }
                }
            }
        }
