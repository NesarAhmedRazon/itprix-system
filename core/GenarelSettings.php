<?php

namespace ITP;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

class GenarelSettings
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'make_settings_page']);
        add_action('admin_init', [$this, 'settings']);
        add_action('admin_enqueue_scripts', [$this, 'styles']);
        add_action('admin_head', [$this, 'inline_css']);
    }

    public function make_settings_page()
    {

        add_menu_page(
            __('ITPrix System', 'itprix'), // page <title>Title</title>
            __('ITPrix', 'itprix'), // menu link text
            'administrator', // capability to access the page
            'itprix', // page URL slug
            [$this, 'homeHtml'], // callback function /w content
            'none',
            1
        );
        add_submenu_page(
            'itprix',
            __('ITPrix System', 'itprix'), // page <title>Title</title>
            __('Meta Fixer', 'itprix'), // menu link text
            'administrator', // capability to access the page
            'itprix-metas', // page URL slug
            [$this, 'homeHtml'], // callback function /w content

        );
    }
    public function styles($hook)
    {

        wp_register_style('itprix-admin', plugin_dir_url(__DIR__) . 'style/admin.css');
        wp_enqueue_style('itprix-admin');
    }
    public function homeHtml()
    {
?>
<div class="itprix-home">
    this is home page
</div>
<?php
    }
    public function settings()
    {

        register_setting(
            'custome_post_type_settings', // settings group name
            'custome_post_type', // option name
            'sanitize_text_field' // sanitization function
        );
        add_settings_section(
            'create_cpt', // section ID
            'Create Post Type', // title (if needed)
            '', // callback function (if needed)
            'custome_post_type' // page slug
        );
        add_settings_field(
            'custome_post_type',
            'Post type title',
            [$this, 'cpt_html'], // function which prints the field
            'change_post_type', // page slug
            'create_cpt_title', // section ID
            array(
                'label_for' => 'custome_post_type',
                'class' => 'old_type', // for <tr> element
            )
        );
        $this->role_init();
    }
    public function inline_css()
    {
        $css = "<style>#toplevel_page_itprix .toplevel_page_itprix .wp-menu-image{background-image: url(" . plugin_dir_url(__DIR__) . 'assets/itprixLogo.svg' . ");}</style>";
        echo $css;
    }
    public function role_init()
    {
        $wp_roles = new \WP_Roles();
        add_role(
            'shop_owner',
            'Shop Owner',
            array(
                'level_9'                => false,
                'level_8'                => false,
                'level_7'                => true,
                'level_6'                => true,
                'level_5'                => true,
                'level_4'                => true,
                'level_3'                => true,
                'level_2'                => true,
                'level_1'                => true,
                'level_0'                => true,
                'read'                   => true,
                'read_private_pages'     => false,
                'read_private_posts'     => false,
                'edit_posts'             => true,
                'edit_pages'             => false,
                'edit_published_posts'   => false,
                'edit_published_pages'   => false,
                'edit_private_pages'     => false,
                'edit_private_posts'     => false,
                'edit_others_posts'      => false,
                'edit_others_pages'      => false,
                'publish_posts'          => true,
                'publish_pages'          => false,
                'delete_posts'           => false,
                'delete_pages'           => false,
                'delete_private_pages'   => false,
                'delete_private_posts'   => false,
                'delete_published_pages' => false,
                'delete_published_posts' => false,
                'delete_others_posts'    => false,
                'delete_others_pages'    => false,
                'manage_categories'      => true,
                'manage_links'           => true,
                'moderate_comments'      => false,
                'upload_files'           => true,
                'export'                 => false,
                'import'                 => false,
                'list_users'             => true,
                'edit_theme_options'     => false,
            )
        );

        $capabilities = self::get_core_capabilities();

        foreach ($capabilities as $cap_group) {
            foreach ($cap_group as $cap) {
                $wp_roles->add_cap('shop_owner', $cap);
            }
        }
    }
    public static function get_core_capabilities()
    {
        $capabilities = array();

        $capabilities['core'] = array(
            'manage_woocommerce',
            'view_woocommerce_reports',
        );

        $capability_types = array('product', 'shop_order', 'shop_coupon');

        foreach ($capability_types as $capability_type) {

            $capabilities[$capability_type] = array(
                // Post type.
                "edit_{$capability_type}",
                "read_{$capability_type}",
                "delete_{$capability_type}",
                "edit_{$capability_type}s",
                "edit_others_{$capability_type}s",
                "publish_{$capability_type}s",
                //"read_private_{$capability_type}s",
                "delete_{$capability_type}s",
                "delete_private_{$capability_type}s",
                "delete_published_{$capability_type}s",
                "delete_others_{$capability_type}s",
                "edit_private_{$capability_type}s",
                "edit_published_{$capability_type}s",

                // Terms.
                "manage_{$capability_type}_terms",
                "edit_{$capability_type}_terms",
                "delete_{$capability_type}_terms",
                "assign_{$capability_type}_terms",
            );
        }

        return $capabilities;
    }
}

add_action('personal_options_update', 'update_store_meta');
add_action('edit_user_profile_update', 'update_store_meta');
function update_store_meta($user_id)
{
    //global $wpdb;
    $users = new \WP_User(1);
    $users->remove_role('shop_manager');
    $users->set_role('customer');
    if (isset($_POST['has_shop'])) {

        update_user_meta($user_id, 'last_name', $_POST['has_shop']);
    }
    // if (!current_user_can('edit_user', get_current_user_id())) {
    //     return false;
    // }
    // if (isset($_POST['has_shop'])) {
    //     $users->remove_role('customer');
    //     $users->set_role('editor');
    //     //do_action('profile_update', $user_id, 'shop_manager');
    //     update_user_meta($user_id, 'has_shop', $_POST['has_shop']);
    //     update_user_meta($user_id, 'last_name', $_POST['has_shop']);

    //     //update_user_meta($user_id, 'has_shop', sanitize_text_field(wp_unslash($_POST['has_shop'])));
    // }
}
