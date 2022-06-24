<?php

/*
*
* @package ItPrixSystem
*
* Plugin Name: ItPrix Core
* Plugin URI: https://github.com/NesarAhmedRazon/itprix-system
* Description: Custome Functions added by ItPrix
* Author: Nesar Ahmed
* Version: 0.0.1
* Elementor tested up to: 3.6.6
* Elementor Pro tested up to: 3.7.2
* Author URI: https://github.com/NesarAhmedRazon/
* Text Domain: itprix
*/

namespace ITP;

define(
    'ITPRIX_SYSTEM',
    __FILE__
);
require plugin_dir_path(ITPRIX_SYSTEM) . 'core/GenarelSettings.php';
require plugin_dir_path(ITPRIX_SYSTEM) . 'core/CreatePostType.php';
require plugin_dir_path(ITPRIX_SYSTEM) . 'core/Exts.php';
require plugin_dir_path(ITPRIX_SYSTEM) . 'core/EcomSystem.php';

$sets = new GenarelSettings();
$core = new CreatePostType(
    [
        'slug' => 'store',
        'name' => 'Store',
        'icon' => 'dashicons-store',
        'color' => '#F15412',
        'hover' => '#F77E21',
    ]
);

new EcomSystem();
