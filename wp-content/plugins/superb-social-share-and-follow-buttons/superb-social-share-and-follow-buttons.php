<?php
/*
Plugin Name: Superb Social Media Share Buttons and Follow Buttons for WordPress
Description: Social Media Share Buttons & Follow Buttons. Social Share Icons. 25+ Social networks. Icon & Button Shortcodes. Floating Sidebar.
Version: 1.1.1
Text Domain: spbsm
Domain Path: /languages
Author: SuPlugins
Author URI: https://superbthemes.com/
License: GPL2 or later
*/

defined('ABSPATH') || exit;

if (! defined('WPINC')) {
    die;
}

if (! class_exists('spbsm')) {
    include_once dirname(__FILE__) . '/plugin.php';
}

$spbsm_plugin = spbsm::GetInstance('1.1.1', __FILE__);
