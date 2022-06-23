<?php

namespace ITP;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

class CreatePostType
{
    public $slugs;
    public $name;
    public $color = '#9EB23B';
    public function __construct($args)
    {
        $this->slugs = $args['slug'];
        $this->name = $args['name'];
        $this->icon = $args['icon'];
        $this->color = $args['color'];
        $this->hover = $args['hover'];
        add_action('init', [$this, 'makePostType']);
        add_action('admin_enqueue_scripts', [$this, 'styles']);
        add_action('admin_head', [$this, 'inline_css']);
    }
    public function styles($hook)
    {
        wp_register_style('itprix-menu', plugin_dir_url(__DIR__) . 'style/menu.css');
        wp_enqueue_style('itprix-menu');
    }
    public function inline_css()
    {
        $css = "<style>.menu-icon-" . $this->slugs . ",.menu-icon-$this->slugs > a.wp-has-current-submenu{background-color: $this->color!important;}.menu-icon-" . $this->slugs . ":hover{background-color: $this->hover!important;}.menu-icon-" . $this->slugs . " a,.menu-icon-" . $this->slugs . " .wp-menu-image:before{color:#fff!important;}</style>";
        echo $css;
    }
    public function makePostType()
    {
        $labels = array(
            'name'               => _x($this->name . 's', 'post type general name', 'itprix'),
            'singular_name'      => _x($this->name, 'post type singular name', 'itprix'),
            'menu_name'          => _x($this->name . 's', 'admin menu', 'itprix'),
            'name_admin_bar'     => _x($this->name, 'add new on admin bar', 'itprix'),
            'add_new'            => _x('Add New ' . $this->name, 'itprix'),
            'add_new_item'       => __('New ' . $this->name, 'itprix'),
            'new_item'           => __('New ' . $this->name, 'itprix'),
            'edit_item'          => __('Edit ' . $this->name, 'itprix'),
            'view_item'          => __('View ' . $this->name, 'itprix'),
            'all_items'          => __('All ' . $this->name . 's', 'itprix'),
            'search_items'       => __('Search ' . $this->name, 'itprix'),
            'parent_item_colon'  => __('Parent ' . $this->name . ':', 'itprix'),
            'not_found'          => __('No ' . $this->name . ' found.', 'itprix'),
            'not_found_in_trash' => __('No ' . $this->name . ' found in Trash.', 'itprix'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => $this->slugs),
            'capability_type'    => 'page',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 13,
            'menu_icon'          => $this->icon,
            'show_in_rest'       => true,
            'supports'           => array('title', 'editor', 'thumbnail')

        );

        register_post_type($this->slugs, $args);
    }
}
