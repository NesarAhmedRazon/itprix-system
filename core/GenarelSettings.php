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
        if ('itprix' != $hook) {
            return;
        }
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
    }
    public function inline_css()
    {
        $css = "<style>#toplevel_page_itprix .toplevel_page_itprix .wp-menu-image{background-image: url(" . plugin_dir_url(__DIR__) . 'assets/itprixLogo.svg' . ");}</style>";
        echo $css;
    }
}
