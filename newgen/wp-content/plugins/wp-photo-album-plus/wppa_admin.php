<?php 
/* wppa_admin.php
* Package: wp-photo-album-plus
*
* Contains all the admin pages
* Version 3.0.0
*/

/* Add admin style */
add_action('admin_init', 'wppa_admin_styles');

function wppa_admin_styles() {
	wp_register_style('wppa_admin_style', '/wp-content/plugins/' . WPPA_PLUGIN_PATH . '/admin_styles.css');
	wp_enqueue_style('wppa_admin_style');
}

/* Add java scripts */
add_action('admin_init', 'wppa_admin_scripts');

function wppa_admin_scripts() {
	wp_register_script('wppa_upload_script', '/wp-content/plugins/' . WPPA_PLUGIN_PATH . '/multifile_compressed.js');
	wp_enqueue_script('wppa_upload_script');
	wp_register_script('wppa_admin_script', '/wp-content/plugins/' . WPPA_PLUGIN_PATH . '/admin_scripts.js');
	wp_enqueue_script('wppa_admin_script');
	wp_enqueue_script('jquery');
}

require_once('wppa_albumadmin.php');
require_once('wppa_upload.php');
require_once('wppa_settings.php');
require_once('wppa_widgetadmin.php');
require_once('wppa_help.php');
require_once('wppa_adminfunctions.php');
require_once('wppa_export.php');
	
