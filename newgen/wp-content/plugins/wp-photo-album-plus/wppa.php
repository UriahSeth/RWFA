<?php
/*
Plugin Name: WP Photo Album Plus
Description: Easily manage and display your photo albums and slideshows within your WordPress site.
Version: 3.0.5
Author: J.N. Breetvelt a.k.a OpaJaap
Author URI: http://www.opajaap.nl/
Plugin URI: http://wordpress.org/extend/plugins/wp-photo-album-plus/
*/

global $wppa_revno; $wppa_revno = '305';
global $wpdb;

/* DEFINES 
/*
/* Check for php version
/* PHP_VERSION_ID is available as of PHP 5.2.7, if our 
/* version is lower than that, then emulate it
*/
if (!defined('PHP_VERSION_ID')) {
	$version = explode('.', PHP_VERSION);

	define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}
define('WPPA_ALBUMS', $wpdb->prefix . 'wppa_albums');
define('WPPA_PHOTOS', $wpdb->prefix . 'wppa_photos');
define('WPPA_RATING', $wpdb->prefix . 'wppa_rating');
define('WPPA_PLUGIN_PATH', 'wp-photo-album-plus');
define('WPPA_NONCE' , 'wppa-update-check');

/* FORM SECURITY */
function wppa_nonce_field($action = -1, $name = 'wppa-update-check') { 
	return wp_nonce_field($action, $name); 
}
function wppa_check_admin_referer($arg1, $arg2) {
	check_admin_referer($arg1, $arg2);
}

/* SETUP */
// calls the setup function on activation
register_activation_hook( __FILE__, 'wppa_setup' );

// does the initial setup
function wppa_setup() {
	global $wpdb;
	global $wppa_revno;
	
	$old_rev = get_option('wppa_revision', '100');
	if ($old_rev <= $wppa_revno) {
		
	$create_albums = "CREATE TABLE " . WPPA_ALBUMS . " (
                    id bigint(20) NOT NULL auto_increment, 
                    name text NOT NULL, 
                    description text NOT NULL, 
                    a_order smallint(5) unsigned NOT NULL, 
                    main_photo bigint(20) NOT NULL, 
                    a_parent bigint(20) NOT NULL,
                    p_order_by int unsigned NOT NULL,
					cover_linkpage bigint(20) NOT NULL,
					owner text NOT NULL,
                    PRIMARY KEY  (id) 
                    );";
                    
	$create_photos = "CREATE TABLE " . WPPA_PHOTOS . " (
                    id bigint(20) NOT NULL auto_increment, 
                    album bigint(20) NOT NULL, 
                    ext tinytext NOT NULL, 
                    name text NOT NULL, 
                    description longtext NOT NULL, 
                    p_order smallint(5) unsigned NOT NULL,
					mean_rating tinytext NOT NULL,
					linkurl text NOT NULL,
					linktitle text NOT NULL,
                    PRIMARY KEY  (id) 
                    );";

	$create_rating = "CREATE TABLE " . WPPA_RATING . " (
					id bigint(20) NOT NULL auto_increment,
					photo bigint(20) NOT NULL,
					value smallint(5) NOT NULL,
					user text NOT NULL,
					PRIMARY KEY  (id)
					);";
					
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($create_albums);
    dbDelta($create_photos);
	dbDelta($create_rating);
	
	wppa_set_defaults();
	wppa_check_dirs();
	
	$iret = true;
	
	if ($old_rev < '302') {		// hide is obsolete, we use eneble now
		$opt = get_option('wppa_hide_slideshow', 'nil');
		if ($opt != 'nil') {
			if ($opt == 'yes') update_option('wppa_enable_slideshow', 'no');
			else update_option('wppa_enable_slideshow', 'yes');
			delete_option('wppa_hide_slideshow');
		}
		$opt = get_option('wppa_toptenwidgettitle', 'nil');	// obsolete
		if ($opt != 'nil') {
			delete_option('wppa_toptenwidgettitle');
		}
		$opt = get_option('wppa_black', 'nil'); // obsolete
		if ($opt != 'nil') {
			update_option('wppa_fontcolor_box', $opt);
			delete_option('wppa_black');
		}
	}
	
	if ($old_rev < '300') {		// theme and/or css changed since...
		$key = '0';
		$userstyle = ABSPATH . 'wp-content/themes/' . get_option('template') . '/wppa_style.css';
		$usertheme = ABSPATH . 'wp-content/themes/' . get_option('template') . '/wppa_theme.php';
		if (is_file($userstyle)) $key += '1';
		if (is_file($usertheme)) $key += '2';
		update_option('wppa_update_key', $key);
		}
	
	if ($old_rev < '243') {		// ownerfield added in...
		global $current_user;
		get_currentuserinfo();
		$user = $current_user->user_login;
		$query = $wpdb->prepare('UPDATE `' . WPPA_ALBUMS . '` SET `owner` = %s WHERE `owner` = %s', $user, '');
		$iret = $wpdb->query($query);
		}
		
	if ($iret !== false) update_option('wppa_revision', $wppa_revno);
	
	}
}
	
/* LOAD SIDEBAR WIDGETS */
require_once('wppa_widget.php');
require_once('wppa_searchwidget.php');
require_once('wppa_toptenwidget.php');
require_once('wppa_slideshow_widget.php');
require_once('wppa_gp_widget.php');

/* ADMIN MENU */
add_action('admin_menu', 'wppa_add_admin');

function wppa_add_admin() {
	global $wp_roles;

	if (current_user_can('administrator')) {	// Make sure admin has access rights
		$wp_roles->add_cap('administrator', 'wppa_admin');
		$wp_roles->add_cap('administrator', 'wppa_upload');
		$wp_roles->add_cap('administrator', 'wppa_sidebar_admin');
	}

	$iconurl = get_bloginfo('wpurl') . '/wp-content/plugins/' . WPPA_PLUGIN_PATH . '/images/camera16.png';
	add_menu_page('WP Photo Album', __('Photo&thinsp;Albums', 'wppa'), 'wppa_admin', __FILE__, 'wppa_admin', $iconurl);
	
    add_submenu_page(__FILE__, __('Upload Photos', 'wppa'), __('Upload Photos', 'wppa'), 'wppa_upload', 'upload_photos', 'wppa_page_upload');
	add_submenu_page(__FILE__, __('Import Photos', 'wppa'), __('Import Photos', 'wppa'), 'wppa_upload', 'import_photos', 'wppa_page_import');
	add_submenu_page(__FILE__, __('Export Photos', 'wppa'), __('Export Photos', 'wppa'), 'administrator', 'export_photos', 'wppa_page_export');
    add_submenu_page(__FILE__, __('Settings', 'wppa'), __('Settings', 'wppa'), 'administrator', 'options', 'wppa_page_options');
	add_submenu_page(__FILE__, __('Photo of the day Widget', 'wppa'), __('Photo of the day', 'wppa'), 'wppa_sidebar_admin', 'wppa_sidebar_options', 'wppa_sidebar_page_options');
    add_submenu_page(__FILE__, __('Help &amp; Info', 'wppa'), __('Help &amp; Info', 'wppa'), 'edit_posts', 'wppa_help', 'wppa_page_help');
}

/* ADMIN PAGES */
if (is_admin()) require_once('wppa_admin.php');

/* API FILTER and FUNCTIONS */
if (!is_admin()) {
	require_once('wppa_filter.php');
	require_once('wppa_functions.php');
}

/* COMMON FUNCTIONS */
require_once('wppa_commonfunctions.php');

/* LOAD STYLESHEET */
if (!is_admin()) add_action('wp_print_styles', 'wppa_add_style');

function wppa_add_style() {
	$userstyle = ABSPATH . 'wp-content/themes/' . get_option('template') . '/wppa_style.css';
	if (is_file($userstyle)) {
		wp_register_style('wppa_style', '/wp-content/themes/' . get_option('template')  . '/wppa_style.css');
		wp_enqueue_style('wppa_style');
	} else {
		wp_register_style('wppa_style', '/wp-content/plugins/' . WPPA_PLUGIN_PATH . '/theme/wppa_style.css');
		wp_enqueue_style('wppa_style');
	}
}

/* LOAD SLIDESHOW and DYNAMIC STYLES */
if (!is_admin()) add_action('init', 'wppa_add_javascripts');

function wppa_add_javascripts() {
	wp_register_script('wppa_slideshow', '/wp-content/plugins/' . WPPA_PLUGIN_PATH . '/theme/wppa_slideshow.js');
	wp_register_script('wppa_theme_js', '/wp-content/plugins/' . WPPA_PLUGIN_PATH . '/theme/wppa_theme.js');
	wp_enqueue_script('jquery');
	wp_enqueue_script('wppa_slideshow');
	wp_enqueue_script('wppa_theme_js');
}

/* LOAD WPPA+ THEME */
if (!is_admin()) add_action('init', 'wppa_load_theme');

function wppa_load_theme() {
	$templatefile = ABSPATH.'wp-content/themes/'.get_option('template').'/wppa_theme.php';
	if (is_file($templatefile)) {
		require_once($templatefile);
	} else {
		require_once(ABSPATH.'wp-content/plugins/'.WPPA_PLUGIN_PATH.'/theme/wppa_theme.php');
	}
}

/* Set up $wppa[], $wppa_opt[] and loads wppa language file, hopefully after q_translate is set up */
add_action('init', 'wppa_initialize_runtime', '100');

/* Enable rendering wppa after possible metatags with post content is done */
add_action('wp_head', 'wppa_enable_rendering', '100');