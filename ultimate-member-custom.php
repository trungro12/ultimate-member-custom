<?php

/**
 * @package UltimateMemberCustom
 */
/*
Plugin Name: Ultimate Member Custom
Description: Custom cho user có nhiều tính năng hơn
Version: 1.0
Requires at least: 5.0
Requires PHP: 5.2
Author: Trung Pham
License: GPLv2 or later
*/

if (!function_exists('add_action')) {
	echo 'Không thể chạy plugins trong website!';
	exit;
}
define('ULTIMATEMEMBER_CUSTOM__FILETYPE', ['pdf', 'txt']);
define('ULTIMATEMEMBER_CUSTOM__PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ULTIMATEMEMBER_CUSTOM__PLUGIN_URL', plugin_dir_url(__FILE__));

require_once(ULTIMATEMEMBER_CUSTOM__PLUGIN_DIR . '/lib/core.php');
add_action('init', array('UltimateMemberCustom', 'init'));
