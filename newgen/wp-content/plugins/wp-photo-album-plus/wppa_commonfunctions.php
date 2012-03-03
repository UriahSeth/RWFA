<?php
/* wppa_commonfinctions.php
*
* Functions used in admin and in themes
* version 3.0.5
*/
global $wppa_api_version;
$wppa_api_version = '3-0-5-001';
// Initialize globals and option settings
function wppa_initialize_runtime($force = false) {
global $wppa;
global $wppa_opt;
global $wppa_revno;
global $wppa_api_version;

	if ($force) {
		$wppa = false; // destroy existing arrays
		$wppa_opt = false;
	}

	if (!is_array($wppa)) {
		$wppa = array (
			'debug' => false,
			'revno' => $wppa_revno,				// set in wppa.php
			'api_version' => $wppa_api_version,	// set in wppa_functions.php
			'fullsize' => '',
			'enlarge' => false,
			'occur' => '0',
			'master_occur' => '0',
			'widget_occur' => '0',
			'in_widget' => false,
			'is_cover' => '0',
			'is_slide' => '0',
			'is_slideonly' => '0',
			'film_on' => '0',
			'single_photo' => '',
			'is_mphoto' => '0',
			'start_album' => '',
			'align' => '',
			'src' => false,
			'portrait_only' => false,
			'in_widget_linkurl' => '',
			'in_widget_linktitle' => '',
			'in_widget_timeout' => '0',
			'ss_widget_valign' => '',
			'album_count' => '0',
			'thumb_count' => '0',
			'out' => '',
			'auto_colwidth' => false,
			'permalink' => '',
			'randseed' => time() % '4711',
			'rendering_enabled' => false
		);
		if (isset($_POST['wppa-searchstring'])) $wppa['src'] = true;
		if (isset($_GET['wppa_src'])) $wppa['src'] = true;
	}
	
	if (!is_array($wppa_opt)) {
		$wppa_opt = array ( 
			'wppa_revision' => '',
			'wppa_fullsize' => '',
			'wppa_colwidth' => '',
			'wppa_maxheight' => '',
			'wppa_enlarge' => '',
			'wppa_resize_on_upload' => '',
			'wppa_fullvalign' => '',
			'wppa_fullhalign' => '',
			'wppa_min_thumbs' => '',
			'wppa_thumbtype' => '',
			'wppa_valign' => '',
			'wppa_thumbsize' => '',
			'wppa_tf_width' => '',
			'wppa_tf_height' => '',
			'wppa_tn_margin' => '',
			'wppa_smallsize' => '',
			'wppa_show_bread' => '',
			'wppa_show_home' => '',
			'wppa_bc_separator' => '',
			'wppa_use_thumb_opacity' => '',
			'wppa_thumb_opacity' => '',
			'wppa_use_thumb_popup' => '',
			'wppa_use_cover_opacity' => '',
			'wppa_cover_opacity' => '',
			'wppa_animation_speed' => '',
			'wppa_slideshow_timeout' => '',
			'wppa_bgcolor_even' => '',
			'wppa_bgcolor_alt' => '',
			'wppa_bgcolor_nav' => '',
			'wppa_bgcolor_img' => '',
			'wppa_bcolor_even' => '',
			'wppa_bcolor_alt' => '',
			'wppa_bcolor_nav' => '',
			'wppa_bwidth' => '',
			'wppa_bradius' => '',
			'wppa_fontfamily_thumb' => '',
			'wppa_fontsize_thumb' => '',
			'wppa_fontcolor_thumb' => '',
			'wppa_fontfamily_box' => '',
			'wppa_fontsize_box' => '',
			'wppa_fontcolor_box' => '',
			'wppa_fontfamily_nav' => '',
			'wppa_fontsize_nav' => '',
			'wppa_fontcolor_nav' => '',
			'wppa_fontfamily_title' => '',
			'wppa_fontsize_title' => '',
			'wppa_fontcolor_title' => '',
			'wppa_fontfamily_fulldesc' => '',
			'wppa_fontsize_fulldesc' => '',
			'wppa_fontcolor_fulldesc' => '',
			'wppa_fontfamily_fulltitle' => '',
			'wppa_fontsize_fulltitle' => '',
			'wppa_fontcolor_fulltitle' => '',
			'wppa_arrow_color' => '',
			'wppa_widget_padding_top' => '',
			'wppa_widget_padding_left' => '',
			'wppa_2col_treshold' => '',
			'wppa_3col_treshold' => '',
			'wppa_film_show_glue' => '',
			'wppa_album_page_size' => '',
			'wppa_thumb_page_size' => '',
			'wppa_thumb_auto' => '',
			'wppa_coverphoto_left' => '',
			'wppa_thumbphoto_left' => '',
			'wppa_enable_slideshow' => '',
			'wppa_thumb_text_name' => '',
			'wppa_thumb_text_desc' => '',
			'wppa_thumb_text_rating' => '',
			'wppa_show_startstop_navigation' => '',
			'wppa_show_browse_navigation' => '',
			'wppa_show_full_desc' => '',
			'wppa_show_full_name' => '',
			'wppa_start_slide' => '',
			'wppa_hide_slideshow' => '',
			'wppa_filmstrip' => '',
			'wppa_bc_url' => '',
			'wppa_bc_txt' => '',
			'wppa_topten_count' => '',
			'wppa_topten_size' => '',
			'wppa_excl_sep' => '',
			'wppa_rating_on' => '',
			'wppa_rating_login' => '',
			'wppa_rating_change' => '',
			'wppa_rating_multi' => '',
			'wppa_list_albums_by' => '',
			'wppa_list_albums_desc' => '',
			'wppa_list_photos_by' => '',
			'wppa_list_photos_desc' => '',
			'wppa_html' => '',
			'wppa_thumb_linkpage' => '',
			'wppa_thumb_linktype' => '',
			'wppa_mphoto_linkpage' => '',
			'wppa_mphoto_linktype' => '',
			'wppa_fadein_after_fadeout' => '',
			'wppa_widget_linkpage' => '',
			'wppa_widget_linktype' => '',
			'wppa_slideonly_widget_linkpage' => '',
			'wppa_slideonly_widget_linktype' => '',
			'wppa_topten_widget_linkpage' => '',
			'wppa_topten_widget_linktype' => '',
			'wppa_coverimg_linktype' => '',
			'wppa_coverimg_linkpage' => '',
			'wppa_search_linkpage' => '',
			'wppa_chmod' => '',
			'wppa_setup' => '',
			'permalink_structure' => ''			// This must be last
		);
		array_walk($wppa_opt, 'wppa_set_options');
	}
	wppa_load_language();
}
function wppa_set_options($value, $key) {
global $wppa_opt;

	if (is_admin()) {	// admin needs the raw data
		$wppa_opt[$key] = get_option($key);
	}
	else {
		$temp = get_option($key);
		switch ($temp) {
			case 'no':
				$wppa_opt[$key] = false;
				break;
			case 'yes':
				$wppa_opt[$key] = true;
				break;
			default:
				$wppa_opt[$key] = $temp;
			}	
	}
}

function wppa_load_language() {
global $wppa_locale;
global $q_config;
global $wppa;

	if ($wppa_locale) return; // Done already
	
	// See if qTranslate present and actve, if so, get locale there
	if (is_array($q_config)) {	
		if (isset($q_config['language'])) $lang = $q_config['language'];
		if (isset($q_config['locale'][$lang])) $wppa_locale = $q_config['locale'][$lang];
	}
	else {		// Get locale from wp-config
		$wppa_locale = get_locale();
	}
	if ($wppa_locale) {
		$domain = is_admin() ? 'wppa' : 'wppa_theme';
		$mofile = ABSPATH.'wp-content/plugins/'.dirname( plugin_basename( __FILE__ ) ) . '/langs/'.$domain.'-'.$wppa_locale.'.mo';
		$bret = load_textdomain($domain, $mofile);
	}
	
	if ($wppa['debug']) {
		echo('<span style="color:blue"><small>Lang='.$lang.', Locale='.$locale.', Mofile='.$mofile);
		if (is_file($mofile)) echo(' exists.'); else echo(' does not exist.');
		if (!$bret) $bret = '0';
		echo(', loaded='.$bret.'.</small></span><br/>');	// Diagnostic
	}
}

// After this appears in the header, rendering will be to full html
function wppa_enable_rendering() {
global $wppa;
	$wppa['rendering_enabled'] = true;
	echo("\n<!-- WPPA+ Rendering enabled -->\n");
}

// get the url to the plugins image directory
function wppa_get_imgdir() {
	$result = get_bloginfo('wpurl').'/wp-content/plugins/'.WPPA_PLUGIN_PATH.'/images/';
	return $result;
}

// get album order
function wppa_get_album_order() {
global $wppa;

    $result = '';
    $order = get_option('wppa_list_albums_by');
    switch ($order)
    {
    case '1':
        $result = 'ORDER BY a_order';
        break;
    case '2':
        $result = 'ORDER BY name';
        break;  
    case '3':
        $result = 'ORDER BY RAND('.$wppa['randseed'].')';
        break;
    default:
        $result = 'ORDER BY id';
    }
    if (get_option('wppa_list_albums_desc') == 'yes') $result .= ' DESC';
    return $result;
}

// get photo order
function wppa_get_photo_order($id) {
global $wpdb;
global $wppa;
    
	if ($id == 0) $order=0;
	else $order = $wpdb->get_var("SELECT p_order_by FROM " . WPPA_ALBUMS . " WHERE id=$id");
    if ($order == '0') $order = get_option('wppa_list_photos_by');
    switch ($order)
    {
    case '1':
        $result = 'ORDER BY p_order';
        break;
    case '2':
        $result = 'ORDER BY name';
        break;
    case '3':
        $result = 'ORDER BY RAND('.$wppa['randseed'].')';
        break;
	case '4':
		$result = 'ORDER BY mean_rating';
		break;
    default:
        $result = 'ORDER BY id';
    }
    if (get_option('wppa_list_photos_desc') == 'yes') $result .= ' DESC';
    return $result;
}

function wppa_get_rating_count_by_id($id = '') {
global $wpdb;

	if (!is_numeric($id)) return '';
	$query = 'SELECT * FROM '.WPPA_RATING.' WHERE photo = '.$id;
	$ratings = $wpdb->get_results($query, 'ARRAY_A');
	if ($ratings) return count($ratings);
	else return '0';
}

function wppa_get_rating_by_id($id = '', $opt = '') {
global $wpdb;

	$result = '';
	if (is_numeric($id)) {
		$rating = $wpdb->get_var("SELECT mean_rating FROM ".WPPA_PHOTOS." WHERE id=$id");
		if ($rating) {
			if ($opt == 'nolabel') $result = round($rating * 1000) / 1000;
			else $result = sprintf(__a('Rating: %s', 'wppa_theme'), round($rating * 1000) / 1000);
		}
	}
	return $result;
}

// See if an album is another albums ancestor
function wppa_is_ancestor($anc, $xchild) {

	$child = $xchild;
	if (is_numeric($anc) && is_numeric($child)) {
		$parent = wppa_get_parentalbumid($child);
		while ($parent > '0') {
			if ($anc == $parent) return true;
			$child = $parent;
			$parent = wppa_get_parentalbumid($child);
		}
	}
	return false;
}

// Get the albums parent
function wppa_get_parentalbumid($alb) {
global $wpdb;
    
	$query = $wpdb->prepare('SELECT `a_parent` FROM `' . WPPA_ALBUMS . '` WHERE `id` = %d', $alb);
	$result = $wpdb->get_var($query);
	
    if (!is_numeric($result)) {
		$result = 0;
	}
    return $result;
}

// get user
function wppa_get_user() {
global $current_user;

	if (is_user_logged_in()) {
		get_currentuserinfo();
		$user = $current_user->user_login;
		return $user;
	}
	else {
		if (is_admin()) {
			wpa_die('It is not allowed to run admin pages while you are not logged in.');
		}
		else {
			return $_SERVER['REMOTE_ADDR'];
		}
	}
}

function wppa_get_album_id($name = '') {
global $wpdb;

	if ($name == '') return '';
    $name = $wpdb->escape($name);
    $id = $wpdb->get_var("SELECT id FROM " . WPPA_ALBUMS . " WHERE name='" . $name . "'");
    if ($id) {
		return $id;
	}
	else {
		return '';
	}
}

function wppa_get_album_name($id = '', $raw = '') {
global $wpdb;
    
    if ($id == '0') $name = is_admin() ? __('--- none ---', 'wppa') : __a('--- none ---', 'wppa_theme');
    elseif ($id == '-1') $name = is_admin() ? __('--- separate ---', 'wppa') : __a('--- separate ---', 'wppa_theme');
    else {
        if ($id == '') if (isset($_GET['album'])) $id = $_GET['album'];
        $id = $wpdb->escape($id);	
        if (is_numeric($id)) $name = $wpdb->get_var("SELECT name FROM " . WPPA_ALBUMS . " WHERE id=$id");
    }
	if ($name) {
		if ($raw != 'raw') $name = stripslashes($name);
	}
	else {
		$name = '';
	}
	if (!is_admin()) $name = wppa_qtrans($name);
	return $name;
}

function wppa_is_wider($x, $y) {

	$ratioref = get_option('wppa_fullsize') / get_option('wppa_maxheight');
	$ratio = $x / $y;
	return ($ratio > $ratioref);
}

// qtrans hook for multi language support of content
function wppa_qtrans($output, $lang = '') {
	if ($lang == '') {
		if (function_exists('qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage')) {
			$output = qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage($output);
		}
	} else {
		if (function_exists('qtrans_use')) {
			$output = qtrans_use($lang, $output, false);
		}
	}
	return $output;
}

