<?php
/* wppa_functions.php
* Pachkage: wp-photo-album-plus
*
* Various funcions and API modules
* Version 3.0.5
*
* 001: Fixed erroneous link in albumcover
*/
/* Moved to wppa_commonfunctions.php:
global $wppa_api_version;
$wppa_api_version = '3-0-5-001';
*/
/* show system statistics */
function wppa_statistics() {
global $wppa;

	$wppa['out'] .= wppa_get_statistics();
}
function wppa_get_statistics() {

	$count = wppa_get_total_album_count();
	$y_id = wppa_get_youngest_album_id();
	$y_name = wppa_get_album_name($y_id);
	$p_id = wppa_get_parentalbumid($y_id);
	$p_name = wppa_get_album_name($p_id);
	
	$result = '<div class="wppa-box wppa-nav" style="text-align: center; '.__wcs('wppa-box').__wcs('wppa-nav').'">';
	$result .= __a('There are', 'wppa_theme').' '.$count.' '.__a('photo albums. The last album added is', 'wppa_theme').' ';
	$result .= '<a href="'.wppa_get_permalink().'album='.$y_id.'&amp;cover=0&amp;occur=1">'.$y_name.'</a>';

	if ($p_id > '0') {
		$result .= __a(', a subalbum of', 'wppa_theme').' '; 
		$result .= '<a href="'.wppa_get_permalink().'album='.$p_id.'&amp;cover=0&amp;occur=1">'.$p_name.'</a>';
	}
	
	$result .= '.</div>';
	
	return $result;
}

/* shows the breadcrumb navigation */
function wppa_breadcrumb($opt = '') {
global $wppa;
global $wppa_opt;

	/* See if they need us */
	if ($opt == 'optional' && !$wppa_opt['wppa_show_bread']) return;	/* Nothing to do here */
	if (wppa_page('oneofone')) return; /* Never at a single image */
	if ($wppa['is_slideonly'] == '1') return;	/* Not when slideony */
	if ($wppa['in_widget']) return; /* Not in a widget */
	if (is_feed()) return;	/* Not in a feed */
	
	/* Compute the seperator */
	$temp = $wppa_opt['wppa_bc_separator'];
	switch ($temp) {
		case 'url':
			$size = $wppa_opt['wppa_fontsize_nav'];
			if ($size != '') $style = 'height:'.$size.'px;';
			else $style = '';
			$sep = ' <img src="'.$wppa_opt['wppa_bc_url'].'" class="no-shadow" style="'.$style.'" /> ';
			break;
		case 'txt':
			$sep = ' '.html_entity_decode(stripslashes($wppa_opt['wppa_bc_txt']), ENT_QUOTES).' ';
			break;
		default:
			$sep = ' &' . $temp . '; ';
	}

	$occur = isset($_GET['occur']) ? $_GET['occur'] : '1';
	$this_occur = ($occur == $wppa['occur']);
	
    if (isset($_GET['album']) && $this_occur) $alb = $_GET['album']; 
	elseif (is_numeric($wppa['start_album'])) $alb = $wppa['start_album'];
	else $alb = 0;
	$separate = wppa_is_separate($alb);

	// See if we link to covers or to contents
	$to_cover = $wppa_opt['wppa_thumbtype'] == 'none' ? '1' : '0';
	
	$wppa['out'] .= '<div id="wppa-bc-'.$wppa['master_occur'].'" class="wppa-nav wppa-box wppa-nav-text" style="'.__wcs('wppa-nav').__wcs('wppa-box').__wcs('wppa-nav-text').'">';

		if ($wppa_opt['wppa_show_home']) {
			$wppa['out'] .= '<a href="'.get_bloginfo('url').'" class="wppa-nav-text" style="'.__wcs('wppa-nav-text').'" >'.__a('Home', 'wppa_theme').'</a>';
			$wppa['out'] .= '<span class="wppa-nav-text" style="'.__wcs('wppa-nav-text').'" >'.$sep.'</span>';	
		}
		
		if (is_page()) wppa_page_breadcrumb($sep);	
	
		if ($alb == 0) {
			if (!$separate) {
				$wppa['out'] .= '<span class="wppa-nav-text wppa-black b1" style="'.__wcs('wppa-nav-text').__wcs('wppa-black').'" >'.the_title('', '', false).'</span>';
			}
		} else {	/* $alb != 0 */
			if (!$separate) {
				$wppa['out'] .= '<a href="'.wppa_get_permalink().'occur='.$wppa['occur'].'" class="wppa-nav-text b2" style="'.__wcs('wppa-nav-text').'" >'.the_title('', '', false).'</a>';
				$wppa['out'] .= '<span class="wppa-nav-text b3" style="'.__wcs('wppa-nav-text').'" >'.$sep.'</span>';
			}
		    wppa_crumb_ancestors($sep, $alb, $wppa['occur'], $to_cover);
			if (wppa_page('oneofone')) {
				$photo = $wppa['single_photo'];
			}
			elseif (wppa_page('single')) {
				if (isset($_GET['photo'])) {
					$photo = $_GET['photo'];
				}
				else {
					$photo = '';
				}
			}
			else {
				$photo = '';
			}
		
			if (is_numeric($photo) && $this_occur) {
				$wppa['out'] .= '<a href="'.wppa_get_permalink().'album='.$alb.'&amp;cover='.$to_cover.'&amp;occur='.$wppa['occur'].'" class="wppa-nav-text b4" style="'.__wcs('wppa-nav-text').'" >'.wppa_get_album_name($alb).'</a>';
				$wppa['out'] .= '<span class="b5" >'.$sep.'</span>';
				$wppa['out'] .= '<span id="bc-pname-'.$wppa['occur'].'" class="wppa-nav-text wppa-black b8" style="'.__wcs('wppa-nav-text').__wcs('wppa-black').'" >'.wppa_get_photo_name($photo).'</span>';
			} elseif ($this_occur && !wppa_page('albums')) {
				$wppa['out'] .= '<a href="'.wppa_get_permalink().'album='.$alb.'&amp;cover='.$to_cover.'&amp;occur='.$wppa['occur'].'" class="wppa-nav-text b6" style="'.__wcs('wppa-nav-text').'" >'.wppa_get_album_name($alb).'</a>';
				$wppa['out'] .= '<span class="b7" >'.$sep.'</span>';
				$wppa['out'] .= '<span id="bc-pname-'.$wppa['occur'].'" class="wppa-nav-text wppa-black b9" style="'.__wcs('wppa-nav-text').__wcs('wppa-black').'" >'.__a('Slideshow', 'wppa_theme').'</span>';
			} else {	// NOT This occurance OR album
				$wppa['out'] .= '<span class="wppa-nav-text wppa-black b10" style="'.__wcs('wppa-nav-text').__wcs('wppa-black').'" >'.wppa_get_album_name($alb).'</span>';
			} 
		}
		if (isset($_POST['wppa-searchstring'])) {
			$wppa['out'] .= '<span class="wppa-nav-text b11" style="'.__wcs('wppa-nav-text').__wcs('wppa-black').'" ><b>&nbsp;'.__a('Searchstring:', 'wppa_theme').'&nbsp;'.$_POST['wppa-searchstring'].'</b></span>';
		}
		elseif (isset($_GET['topten'])) {
			$wppa['out'] .= '<span class="wppa-nav-text b11" style="'.__wcs('wppa-nav-text').__wcs('wppa-black').'" ><b>&nbsp;'.__a('Top rated photos', 'wppa_theme').'</b></span>';
		}
	$wppa['out'] .= '</div>';
}
function wppa_crumb_ancestors($sep, $alb, $occur, $to_cover) {
global $wppa;
	
    $parent = wppa_get_parentalbumid($alb);
    if ($parent < 1) return;
    
    wppa_crumb_ancestors($sep, $parent, $wppa['occur'], $to_cover);
    $wppa['out'] .= '<a href="'.wppa_get_permalink().'album='.$parent.'&amp;cover='.$to_cover.'&amp;occur='.$occur.'" class="wppa-nav-text b20" style="'.__wcs('wppa-nav-text').'" >'.wppa_get_album_name($parent).'</a>';
	$wppa['out'] .= '<span class="wppa-nav-text" style="'.__wcs('wppa-nav-text').'">'.$sep.'</span>';
    return;
}
function wppa_page_breadcrumb($sep) {
global $wpdb;
	
	if (isset($_REQUEST['page_id'])) $page = $_REQUEST['page_id'];
	else $page = '0';

	wppa_crumb_page_ancestors($sep, $page); 
}
function wppa_crumb_page_ancestors($sep, $page = '0') {
global $wpdb;
global $wppa;
	
	$query = "SELECT post_parent FROM " . $wpdb->posts . " WHERE post_type = 'page' AND post_status = 'publish' AND id = " . $page . " LIMIT 0,1";
	$parent = $wpdb->get_var($query);
	if (!is_numeric($parent) || $parent == '0') return;
	wppa_crumb_page_ancestors($sep, $parent);
	$query = "SELECT post_title FROM " . $wpdb->posts . " WHERE post_type = 'page' AND post_status = 'publish' AND id = " . $parent . " LIMIT 0,1";
	$title = $wpdb->get_var($query);
	if (!$title) {
		$title = '****';		// Page exists but is not publish
		$wppa['out'] .= '<a href="#" class="wppa-nav-text b30" style="'.__wcs('wppa-nav-text').'" ></a>';
		$wppa['out'] .= '<span class="wppa-nav-text b31" style="'.__wcs('wppa-nav-text').'" >'.$title.$sep.'</span>';
	} else {
		$wppa['out'] .= '<a href="'.get_page_link($parent).'" class="wppa-nav-text b32" style="'.__wcs('wppa-nav-text').'" >'.$title.'</a>';
		$wppa['out'] .= '<span class="wppa-nav-text b32" style="'.__wcs('wppa-nav-text').'" >'.$sep.'</span>';
	}
}

// Get the albums by calling the theme module and do some parameter processing
// This is the main entrypoint for the wppa+ invocation, either 'by hand' or through the filter.
// As of version 3.0.0 this routine returns the entire html created by the invocation.
function wppa_albums($xid = '', $typ='', $siz = '', $ali = '') {
global $wppa;

	$wppa['occur']++;
	$wppa['master_occur']++;
	if ($wppa['in_widget']) $wppa['widget_occur']++;
	
	if ($typ == 'album') {
		$wppa['is_cover'] = '0';
		$wppa['is_slide'] = '0';
		$wppa['is_slideonly'] = '0';
	}
	elseif ($typ == 'cover') {
		$wppa['is_cover'] = '1';
		$wppa['is_slide'] = '0';
		$wppa['is_slideonly'] = '0';
	}
	elseif ($typ == 'slide') {
		$wppa['is_cover'] = '0';
		$wppa['is_slide'] = '1';
		$wppa['is_slideonly'] = '0';
	}
	elseif ($typ == 'slideonly') {
		$wppa['is_cover'] = '0';
		$wppa['is_slide'] = '0';
		$wppa['is_slideonly'] = '1';
	}
	
	if ($typ == 'photo') {
		$wppa['is_cover'] = '0';
		$wppa['is_slide'] = '0';
		$wppa['is_slideonly'] = '0';
		if (is_numeric($xid)) {
			$wppa['single_photo'] = $xid;
		}
	}
	else {
		if (is_numeric($xid)) {
			$wppa['start_album'] = $xid;
		}
	}
	
	if (is_numeric($siz)) {
		$wppa['fullsize'] = $siz;
	}
	elseif ($siz == 'auto') {
		$wppa['auto_colwidth'] = true;
	}
    
	if ($ali == 'left' || $ali == 'center' || $ali == 'right') {
		$wppa['align'] = $ali;
	}
	
	if ($wppa['is_mphoto'] == '1') {
		wppa_mphoto();
		$wppa['is_mphoto'] = '0';
		$wppa['single_photo'] = '';
	}
	else {
		if (function_exists('wppa_theme')) wppa_theme();	// Call the theme module
		else $wppa['out'] = '<span style="color:red">ERROR: Missing function wppa_theme(), check the installation of WPPA+. Remove customized wppa_theme.php</span>';
	}
	$out = $wppa['out'];
	$wppa['out'] = ''; 
	return $out;	
}


// See if an album is in a separate tree
function wppa_is_separate($xalb) {

	if (!is_numeric($xalb)) return false;
		
	$alb = wppa_get_parentalbumid($xalb);
	if ($alb == 0) return false;
	if ($alb == -1) return true;
	return (wppa_is_separate($alb));
}

// determine page
function wppa_page($page) {
global $wppa;

	$occur = '0';
	if ($wppa['in_widget']) {
		if (isset($_GET['woccur'])) if (is_numeric($_GET['woccur'])) $occur = $_GET['woccur'];
	}
	else {
		if (isset($_GET['occur'])) if (is_numeric($_GET['occur'])) $occur = $_GET['occur'];
	}

	$ref_occur = $wppa['in_widget'] ? $wppa['widget_occur'] : $wppa['occur'];
	
	if ($wppa['is_slide'] == '1') $cur_page = 'slide';			// Do slide or single when explixitly on
	elseif ($wppa['is_slideonly'] == '1') $cur_page = 'slide';		// Slideonly is a subset of slide
	elseif (is_numeric($wppa['single_photo'])) $cur_page = 'oneofone';
	elseif ($occur == $ref_occur) {					// Interprete $_GET only if occur is current
		if (isset($_GET['slide'])) $cur_page = 'slide';	
		elseif (isset($_GET['photo'])) {
			if (isset($_GET['album'])) {
				$cur_page = 'single';
			}
			else {
				$cur_page = 'oneofone';
				$wppa['single_photo'] = $_GET['photo'];
			}
		}
		else $cur_page = 'albums';
	}
	else $cur_page = 'albums';	

	if ($cur_page == $page) return true; else return false;
}

// get id of coverphoto. does all testing
function wppa_get_coverphoto_id($xalb = '') {
global $wpdb;
global $album;
	
	if ($xalb == '') {				// default album
		if (isset($album['id'])) $alb = $album['id'];
	}
	else {							// supplied album
		$alb = $xalb;
	}
	if (is_numeric($alb)) {			// find main id
		$id = $wpdb->get_var("SELECT main_photo FROM " . WPPA_ALBUMS . " WHERE id = " . $alb);
	}
	else return false;					// no album, no coverphoto
	if (is_numeric($id) && $id > '0') {		// check if id belongs to album
		$ph_alb = $wpdb->get_var("SELECT album FROM " . WPPA_PHOTOS . " WHERE id = " . $id);
		if ($ph_alb != $alb) {		// main photo does no longer belong to album. Treat as random
			$id = '0';
		}
	}
	if (!is_numeric($id) || $id == '0') {	// random
		$id = $wpdb->get_var("SELECT id FROM " . WPPA_PHOTOS . " WHERE album = " . $alb . " ORDER BY RAND() LIMIT 1");
	}
	return $id;	
}

// get thumb url
function wppa_get_thumb_url_by_id($id = false) {
global $wpdb;

	if ($id == false) return '';	// no id: no url
	$ext = $wpdb->get_var("SELECT ext FROM " . WPPA_PHOTOS . " WHERE id = " . $id);
	if ($ext) {
		$url = get_bloginfo('wpurl') . '/wp-content/uploads/wppa/thumbs/' . $id . '.' . $ext;
	}
	else {
		$url = '';
	}
	return $url;
}

// get thumb path
function wppa_get_thumb_path_by_id($id = false) {
global $wpdb;

	if ($id == false) return '';	// no id: no path
	$ext = $wpdb->get_var("SELECT ext FROM " . WPPA_PHOTOS . " WHERE id = " . $id);
	if ($ext) {
		$path =  ABSPATH . 'wp-content/uploads/wppa/thumbs/' . $id . '.' . $ext;
	}
	else {
		$path = '';
	}
	return $path;
}

// get image url
function wppa_get_image_url_by_id($id = false) {
global $wpdb;

	if ($id == false) return '';	// no id: no url
	$ext = $wpdb->get_var("SELECT ext FROM " . WPPA_PHOTOS . " WHERE id = " . $id);
	if ($ext) {
		$url = get_bloginfo('wpurl') . '/wp-content/uploads/wppa/' . $id . '.' . $ext;
	}
	else {
		$url = '';
	}
	return $url;
}

// get image path
function wppa_get_image_path_by_id($id = false) {
global $wpdb;

	if ($id == false) return '';	// no id: no path
	$ext = $wpdb->get_var("SELECT ext FROM " . WPPA_PHOTOS . " WHERE id = " . $id);
	if ($ext) {
		$path =  ABSPATH . 'wp-content/uploads/wppa/' . $id . '.' . $ext;
	}
	else {
		$path = '';
	}
	return $path;
}

// get page url of current album image
function wppa_get_image_page_url_by_id($id = false) {
global $wpdb;
global $wppa;
	
	if ($id == false) return '';
	$occur = $wppa['in_widget'] ? $wppa['widget_occur'] : $wppa['occur'];
	$w = $wppa['in_widget'] ? 'w' : '';
	$image = $wpdb->get_row("SELECT * FROM " . WPPA_PHOTOS . " WHERE id={$id} LIMIT 1", 'ARRAY_A');
	if ($image) $imgurl = wppa_get_permalink().'album='.$image['album'].'&amp;photo='.$image['id'].'&amp;cover=0&amp;'.$w.'occur='.$occur;	
	else $imgurl = '';
	return $imgurl;
}

// loop album
function wppa_get_albums($album = false, $type = '') {
global $wpdb;
global $wppa;
global $wppa_opt;

	$src = wppa_get_searchstring();
	
	if (strlen($src)) {
		$albs = $wpdb->get_results('SELECT * FROM ' . WPPA_ALBUMS . ' ' . wppa_get_album_order(), 'ARRAY_A');
		$albums = '';
		$idx = '0';
		foreach ($albs as $album) if (!$wppa_opt['wppa_excl_sep'] || $album['a_parent'] != '-1') {
			if (wppa_deep_stristr(wppa_qtrans($album['name']).' '.wppa_qtrans($album['description']), $src)) {
				$albums[$idx] = $album;
				$idx++;
			}
		}
	}
	else {
		if ($wppa['src']) return false;	// empty search string
		$occur = '0';
		if ($wppa['in_widget']) {
			if (isset($_GET['woccur'])) if (is_numeric($_GET['woccur'])) $occur = $_GET['woccur'];
		}
		else {
			if (isset($_GET['occur'])) if (is_numeric($_GET['occur'])) $occur = $_GET['occur'];
		}
		
		// Check if querystring given This has the highest priority in case of matching occurrance
		// Obey querystring only if the global occurence matches the occurence in the querystring, or no query occurrence given.
		$ref_occur = $wppa['in_widget'] ? $wppa['widget_occur'] : $wppa['occur'];
		if (($occur == $ref_occur) && (isset($_GET['album']))) {
			$id = $_GET['album'];
			if (isset($_GET['cover'])) $wppa['is_cover'] = $_GET['cover'];
		}
		// Check if parameters set
		elseif (is_numeric($album)) {
			$id = $album;
			if ($type == 'album') $wppa['is_cover'] = '0';
			if ($type == 'cover') $wppa['is_cover'] = '1';
		}
		// Check if globals set
		elseif (is_numeric($wppa['start_album'])) {
			$id = $wppa['start_album'];
		}
		// The default: all albums with parent = 0;
		else $id = '0';
		
		// Top-level album has no cover
		if ($id == '0') $wppa['is_cover'] = '0';
		
		// Do the query
		if (is_numeric($id)) {
			if ($wppa['is_cover']) $q = $wpdb->prepare('SELECT * FROM ' . WPPA_ALBUMS . ' WHERE id= %d', $id);
			else $q = $wpdb->prepare('SELECT * FROM ' . WPPA_ALBUMS . ' WHERE a_parent= %d '. wppa_get_album_order(), $id);
			$albums = $wpdb->get_results($q, 'ARRAY_A');
		}
		else $albums = false;
	}
	$wppa['album_count'] = count($albums);
	return $albums;
}

// get link to album by id or in loop
function wppa_get_album_url($xid = '', $pag = '') {
global $album;
global $wppa;
	
	$occur = $wppa['in_widget'] ? $wppa['widget_occur'] : $wppa['occur'];
	$w = $wppa['in_widget'] ? 'w' : '';
	
	if ($xid != '') $id = $xid;
	elseif (isset($album['id'])) {
		$id = $album['id'];
	}
	if ($id != '') {
		$link = wppa_get_permalink($pag).'album='.$id.'&amp;cover=0&amp;'.$w.'occur='.$occur;
	}
	else $link = '';
    return $link;
}

// get number of photos in album 
function wppa_get_photo_count($xid = '') {
global $wpdb;
global $album;
    
    if (is_numeric($xid)) $id = $xid; else $id = $album['id'];
    $count = $wpdb->query("SELECT * FROM " . WPPA_PHOTOS . " WHERE album=".$id);
	return $count;
}

// get number of albums in album 
function wppa_get_album_count($xid = '') {
global $wpdb;
global $album;
    
    if (is_numeric($xid)) $id = $xid; else $id = $album['id'];
    $count = $wpdb->query("SELECT * FROM " . WPPA_ALBUMS . " WHERE a_parent=".$id);
    return $count;
}

// get number of albums in system
function wppa_get_total_album_count() {
global $wpdb;
	
	$count = $wpdb->query("SELECT * FROM " . WPPA_ALBUMS);
	return $count;
}

// get youngest album id
function wppa_get_youngest_album_id() {
global $wpdb;
	
	$result = $wpdb->get_var("SELECT id FROM " . WPPA_ALBUMS . " ORDER BY id DESC LIMIT 1");
	return $result;
}

// get youngest album name
function wppa_get_youngest_album_name() {
global $wpdb;
	
	$result = $wpdb->get_var("SELECT name FROM " . WPPA_ALBUMS . " ORDER BY id DESC LIMIT 1");
	return stripslashes($result);
}

// get album name
function wppa_get_the_album_name() {
global $album;
	
	return wppa_qtrans(stripslashes($album['name']));
}

// get album decription
function wppa_get_the_album_desc() {
global $album;
	
	return wppa_qtrans(stripslashes($album['description']));
}

// get link to slideshow (in loop)
function wppa_get_slideshow_url($page = '') {
global $album;
global $wppa;
	
	$occur = $wppa['in_widget'] ? $wppa['widget_occur'] : $wppa['occur'];
	$w = $wppa['in_widget'] ? 'w' : '';
	$link = wppa_get_permalink($page).'album='.$album['id'].'&amp;slide=true'.'&amp;'.$w.'occur='.$occur;
	
	return $link;	
}

// loop thumbs
function wppa_get_thumbs() {
global $wpdb;
global $wppa;
global $wppa_opt;

	$src = wppa_get_searchstring();
		
	if (isset($_GET['topten'])) {
		$max = $wppa_opt['wppa_topten_count'];
		$thumbs = $wpdb->get_results('SELECT * FROM '.WPPA_PHOTOS.' WHERE mean_rating > 0 ORDER BY mean_rating DESC LIMIT '.$max, 'ARRAY_A');
	}
	elseif (strlen($src)) {
		$tmbs = $wpdb->get_results('SELECT * FROM '.WPPA_PHOTOS.' '.wppa_get_photo_order('0'), 'ARRAY_A');
		$thumbs = '';
		$idx = '0';
		foreach ($tmbs as $thumb) {
			if (wppa_deep_stristr(wppa_qtrans($thumb['name']).' '.wppa_qtrans($thumb['description']), $src)) {
				if (!$wppa_opt['wppa_excl_sep'] || (wppa_get_parentalbumid($thumb['album']) != '-1')) {
					$thumbs[$idx] = $thumb;
					$idx++;
				}
			}
		}
	}
	else {
		if ($wppa['src']) return false; 	// empty search string
		$occur = '0';
		if ($wppa['in_widget']) {
			if (isset($_GET['woccur'])) if (is_numeric($_GET['woccur'])) $occur = $_GET['woccur'];
		}
		else {
			if (isset($_GET['occur'])) if (is_numeric($_GET['occur'])) $occur = $_GET['occur'];
		}
		
		// Obey querystring only if the global occurence matches the occurence in the querystring, or no query occurrence given.
		$ref_occur = $wppa['in_widget'] ? $wppa['widget_occur'] : $wppa['occur'];
		if (($occur == $ref_occur) && (isset($_GET['album']))) {
			$id = $_GET['album'];
		}
		elseif (is_numeric($wppa['start_album'])) $id = $wppa['start_album']; 
		else $id = 0;
		if (is_numeric($id)) {
			$thumbs = $wpdb->get_results("SELECT * FROM ".WPPA_PHOTOS." WHERE album=$id ".wppa_get_photo_order($id), 'ARRAY_A'); 
		}
		else {
			$thumbs = false;
		}
	}
	$wppa['thumb_count'] = count($thumbs);
	return $thumbs;
}

// get link from a thumbnail to photo
function w_ppa_get_photo_page_url($type) {
global $thumb;
global $wppa;
global $wppa_opt;
	
	// $type may be 'thumb' or 'mphoto'
	if ($type != 'thumb' && $type != 'mphoto') {
		echo('<span style="color:red">UNEXPECTED ERROR: Wrong argument:'.$type.' in w ppa_get_photo_page_url</span>');
		return '';
	}
	$photo = $type == 'thumb' ? $thumb['id'] : $wppa['single_photo'];
	$pid = $wppa_opt['wppa_'.$type.'_linkpage'];
	
	$album = '0';
	if (isset($_GET['album'])) $album = $_GET['album'];
	else if (!$wppa['src']) $album = wppa_get_album_id_by_photo_id($photo);
	
    if ($album != '0') {
		if ($wppa_opt['wppa_'.$type.'_linktype'] == 'photo') {
			if ($pid == '0' ) {
				$url = wppa_get_permalink().'album='.$album.'&amp;photo='.$photo;
			}
			else {
				$url = wppa_get_permalink($pid).'album='.$album.'&amp;photo='.$photo;
			}
		}
		elseif ($wppa_opt['wppa_'.$type.'_linktype'] == 'single') {
			if ($pid == '0' ) {
				$url = wppa_get_permalink().'photo='.$photo;
			}
			else {
				$url = wppa_get_permalink($pid).'photo='.$photo;
			}
		}
		elseif ($wppa_opt['wppa_'.$type.'_linktype'] == 'album') {
			if ($pid == '0' ) {
				$url = wppa_get_permalink().'album='.$album.'&amp;cover=0';
			}
			else {
				$url = wppa_get_permalink($pid).'album='.$album.'&amp;cover=0';
			}
		}
		elseif ($wppa_opt['wppa_'.$type.'_linktype'] == 'file') {
			$url = wppa_get_photo_url($photo); // .'" target="_blank'; // this doesnt work with popup thumbs
			return $url;
		}
		else return ''; // assume linktype = 'none'
	}
	else {
		$url = wppa_get_permalink().'photo='.$photo;
		if (isset($_POST['wppa-searchstring'])) {
			$url .= '&amp;wppa_src='.$_POST['wppa-searchstring'];
		}
	}
	if ($pid != '0') {	// on a different page
		$occur = '1';
		$w = '';
	}
	else {				// on the same page, post or widget
		$occur = $wppa['in_widget'] ? $wppa['widget_occur'] : $wppa['occur'];
		$w = $wppa['in_widget'] ? 'w' : '';
	}
	$url .= '&amp;'.$w.'occur='.$occur;
//$url .= '&amp;test=4711';
	return $url; 
}

// get url of thumb
function wppa_get_thumb_url() {
global $thumb;

	$url = get_bloginfo('wpurl') . '/wp-content/uploads/wppa/thumbs/' . $thumb['id'] . '.' . $thumb['ext'];
	return $url; 
}

// get path of thumb
function wppa_get_thumb_path() {
global $thumb;
	
	$path = ABSPATH.'wp-content/uploads/wppa/thumbs/'.$thumb['id'].'.'.$thumb['ext'];
	return $path; 
}

// get url of a full sized image
function wppa_get_photo_url($id = '') {
global $wpdb;

    if ($id == '') $id = $_GET['photo'];    
    $id = $wpdb->escape($id);
    
	if (is_numeric($id)) $ext = $wpdb->get_var("SELECT ext FROM ".WPPA_PHOTOS." WHERE id=$id");
	$url = get_bloginfo('wpurl').'/wp-content/uploads/wppa/'.$id.'.'.$ext;
	
	return $url;
}

// get the name of a full sized image
function wppa_get_photo_name($id = '') {
global $wpdb;

	if ($id == '') $id = $_GET['photo'];	
	$id = $wpdb->escape($id);
		
	if (is_numeric($id)) $name = $wpdb->get_var("SELECT name FROM ".WPPA_PHOTOS." WHERE id=$id");
	else $name = '';
	
	return wppa_qtrans($name);
}

// get the description of a full sized image
function wppa_get_photo_desc($id = '') {
global $wpdb;

	if ($id == '') $id = $_GET['photo'];
	$id = $wpdb->escape($id);
	
	if (is_numeric($id)) $desc = $wpdb->get_var("SELECT description FROM ".WPPA_PHOTOS." WHERE id=$id");
	else $desc = '';
	
	return wppa_qtrans($desc);
}

// get full img style
function wppa_get_fullimgstyle($id = '') {
global $wpdb;
global $wppa;
global $wppa_opt;

	if (!is_numeric($wppa['fullsize']) || $wppa['fullsize'] == '0') $wppa['fullsize'] = $wppa_opt['wppa_fullsize'];

	$wppa['enlarge'] = $wppa_opt['wppa_enlarge'];
	
	if (empty($id)) /* if (isset($_GET['photo'])) */ $id = $_GET['photo'];
	if (is_numeric($id)) {
		$ext = $wpdb->get_var("SELECT ext FROM ".WPPA_PHOTOS." WHERE id=$id");
	}
	$img_path = ABSPATH.'wp-content/uploads/wppa/'.$id.'.'.$ext;
	$result = wppa_get_imgstyle($img_path, $wppa['fullsize'], 'optional', 'fullsize');
	return $result;
}

// get slide info
function wppa_get_slide_info($index, $id) {
global $wpdb;
global $wppa;
global $wppa_opt;

	$user = wppa_get_user();
	
	if (isset($_GET['photo'])) {
		$photo = $_GET['photo'];
	}
	else $photo = '0';

	$rating_request = (isset($_GET['rating']) && ($id == $photo));
	$rating_allowed = (!$wppa_opt['wppa_rating_login'] || is_user_logged_in());
	
	if ($rating_request && $wppa_opt['wppa_rating_on'] && $rating_allowed) { // Rating request
		$rating = $_GET['rating'];
		
		if ($rating != '1' && $rating != '2' && $rating != '3' && $rating != '4' && $rating != '5') die(__a('<b>ERROR: Attempt to enter an invalid rating.</b>', 'wppa_theme'));

		$my_oldrat = $wpdb->get_var($wpdb->prepare('SELECT * FROM `'.WPPA_RATING.'` WHERE `photo` = %d AND `user` = %s LIMIT 1', $id, $user)); 

		if ($my_oldrat) {
			if ($wppa_opt['wppa_rating_change']) {	// Modify my vote
				$query = $wpdb->prepare('UPDATE `'.WPPA_RATING.'` SET `value` = %d WHERE `photo` = %d AND `user` = %s LIMIT 1', $rating, $id, $user);
				$iret = $wpdb->query($query);
				if (!$iret) {
//					if (defined('WP_DEBUG')) $wppa['out'] .= ('Unable to update rating. Query = '.$query);
					$myrat = $my_oldrat['value'];
				}
				else {
					$myrat = $rating;
				}
			}
			else if ($wppa_opt['wppa_rating_multi']) {	// Add another vote from me
				$query = $wpdb->prepare('INSERT INTO `'.WPPA_RATING. '` (`id`, `photo`, `value`, `user`) VALUES (0, %d, %s, %s)', $id, $rating, $user);
				$iret = $wpdb->query($query);
				if (!$iret) {
//					if (defined('WP_DEBUG')) $wppa['out'] .= ('Unable to add a rating. Query = '.$query);
					$myrat = $my_oldrat['value'];
				}
				else {
					$query = $wpdb->prepare('SELECT * FROM `'.WPPA_RATING.'`  WHERE `photo` = %d AND `user` = %s', $id, $user);
					$myrats = $wpdb->get_results($query, 'ARRAY_A');
					if (!$myrats) {
						if (defined('WP_DEBUG')) $wppa['out'] .= ('Unable to retrieve ratings. Query = '.$query);
						$myrat = $my_oldrat['value'];
					}
					else {
						$sum = 0;
						$cnt = 0;
						foreach ($myrats as $rt) {
							$sum += $rt['value'];
							$cnt ++;
						}
						if ($cnt > 0) $myrat = $sum/$cnt; else $myrat = $my_oldrat['value'];
					}
				}
			}
		}
		else {	// This is the first and only rating for this photo/user combi
			$iret = $wpdb->query($wpdb->prepare('INSERT INTO `'.WPPA_RATING. '` (`id`, `photo`, `value`, `user`) VALUES (0, %d, %s, %s)', $id, $rating, $user));
//			if (!$iret) {
//				if (defined('WP_DEBUG')) $wppa['out'] .= ('Unable to save rating.');
//			}
			$myrat = $rating;
		}

		// Compute new avgrat
		$ratings = $wpdb->get_results('SELECT * FROM '.WPPA_RATING.' WHERE photo = '.$id, 'ARRAY_A');
		if ($ratings) {
			$sum = 0;
			$cnt = 0;
			foreach ($ratings as $rt) {
				$sum += $rt['value'];
				$cnt ++;
			}
			if ($cnt > 0) $avgrat = $sum/$cnt; else $avgrat = '0';
		}
		else $avgrat = '0';
		// Store it
		// if (defined('WP_DEBUG')) $wppa['out'] .= ('Trying to store '.$avgrat.' to photo #'.$id);
		$query = $wpdb->prepare('UPDATE `'.WPPA_PHOTOS. '` SET `mean_rating` = %s WHERE `id` = %d LIMIT 1', $avgrat, $id);
		$iret = $wpdb->query($query);
//		if (!$iret) if (defined('WP_DEBUG')) $wppa['out'] .= ('Error, could not update avg rating for photo '.$id.'. Query = '.$query);
	}
	else {	// No rating request
		$myrat = $wpdb->get_var($wpdb->prepare('SELECT `value` FROM `'.WPPA_RATING.'` WHERE `photo` = %d AND `user` = %s LIMIT 1', $id, $user)); 
		if (!$myrat) $myrat = '0';
	}
	// Now we know the (updated) $myrat
	// Find the $avgrat
	$avgrat = $wpdb->get_var('SELECT mean_rating FROM '.WPPA_PHOTOS.' WHERE id = '.$id.' LIMIT 1'); 
	if (!$avgrat) $avgrat = '0';
	
	// Compose the rating request url
	$url = wppa_get_permalink('js');
	if (isset($_GET['album'])) $url .= 'album='.$_GET['album'].'&';
	if (isset($_GET['cover'])) $url .= 'cover='.$_GET['cover'].'&';
	if (isset($_GET['slide'])) $url .= 'slide='.$_GET['slide'].'&';
//	if (isset($_GET['occur'])) $url .= 'occur='.$_GET['occur'].'&';
	if ($wppa['in_widget']) {
		$url .= 'woccur='.$wppa['widget_occur'].'&';
	}
	else {
	   $url .= 'occur='.$wppa['occur'].'&';
	}
	if (isset($_GET['topten'])) $url .= 'topten='.$_GET['topten'].'&';
	$url .= 'photo=' . $id . '&rating=';
	
	// check if we are in a ss widget
	// if so: set $wppa['in_widget_linkurl'] and $wppa['in_widget_linktitle'] in all cases except where 'wppa_slideonly_widget_linktype' == 'widget'.
	// In which case it is set by the widget that calls this routine
//	if ($wppa['in_widget'] && $wppa_opt['wppa_slideonly_widget_linktype'] != 'widget') {
//		$ltype = $wppa_opt['wppa_slideonly_widget_linktype'];
//		switch ($ltype) {
//			case 'none':
//				break;
			//	, 'file', 'widget', 'album', 'photo', 'single');
//		}	
//	}

	if ($wppa['in_widget'] == 'ss') {
		$link = wppa_get_imglnk_a('sswidget', $id);
		$linkurl = $link['url'];
		$linktitle = $link['title'];
	}
	else {
		$linkurl = '';
		$linktitle = '';
	}
//echo($linkurl.';'.$linktitle.'<br/>');	
	// Produce final result
    $result = "'".$wppa['master_occur']."','".$index."','".wppa_get_photo_url($id)."','".wppa_get_fullimgstyle($id)."','".esc_js(wppa_get_photo_name($id))."','".wppa_html(esc_js(stripslashes(wppa_get_photo_desc($id))))."','".$id."','".$avgrat."','".$myrat."','".$url."','".$linkurl."','".$linktitle."','".$wppa['in_widget_timeout']."'";
    return $result;                                                        
}

function wppa_get_imgstyle($file, $max_size, $xvalign = '', $type = '') {
global $wppa;
global $wppa_opt;

	if($file == '') return '';					// no image: no dimensions
	if (!is_file($file)) return '';				// no file: no dimensions (2.3.0)
	$result = '';
	$image_attr = getimagesize( $file );
	
	$ratioref = $wppa_opt['wppa_maxheight'] / $wppa_opt['wppa_fullsize'];
	$max_height = round($max_size * $ratioref);

	if ($type == 'fullsize') {
		if ($wppa['portrait_only']) {
			$width = $max_size;
			$height = round($width * $image_attr[1] / $image_attr[0]);
		}
		else {
			if (wppa_is_wider($image_attr[0], $image_attr[1])) {
				$width = $max_size;
				$height = round($width * $image_attr[1] / $image_attr[0]);
			}
			else {
				$height = round($ratioref * $max_size);
				$width = round($height * $image_attr[0] / $image_attr[1]);
			}
			if ($image_attr[0] < $width && $image_attr[1] < $height) {
				if (!$wppa['enlarge']) {
					$width = $image_attr[0];
					$height = $image_attr[1];
				}
			}
		}
	}
	else {
		if (wppa_is_landscape($image_attr)) {
			$width = $max_size;
			$height = round($max_size * $image_attr[1] / $image_attr[0]);
		}
		else {
			$height = $max_size;
			$width = round($max_size * $image_attr[0] / $image_attr[1]);
		}
	}
	
	switch ($type) {
		case 'cover':
			$result .= ' border-width: 0px;';
			$result .= ' width:' . $width . 'px; height:' . $height . 'px;';
			if ($wppa_opt['wppa_use_cover_opacity'] && !is_feed()) {
				$opac = $wppa_opt['wppa_cover_opacity'];
				$result .= ' opacity:' . $opac/100 . '; filter:alpha(opacity=' . $opac . ');';
			}
			break;
		case 'thumb':	// Normal
		case 'ttthumb':	// Topten
		case 'fthumb':	// Filmthumb
			$result .= ' border-width: 0px;';
			$result .= ' width:' . $width . 'px; height:' . $height . 'px;';
			if ($xvalign == 'optional') $valign = $wppa_opt['wppa_valign'];
			else $valign = $xvalign;
			if ($valign != 'default') {	// Center horizontally
				$delta = floor(($max_size - $width) / 2);
				if (is_numeric($valign)) $delta += $valign;
				if ($delta < '0') $delta = '0';
				if ($delta > '0') $result .= ' margin-left:' . $delta . 'px; margin-right:' . $delta . 'px;';
			} 
			switch ($valign) {
				case 'top':
					$result .= ' margin-top: 0px;';
					break;
				case 'center':
					$delta = round(($max_size - $height) / 2);
					if ($delta < '0') $delta = '0';
					$result .= ' margin-top: ' . $delta . 'px;';
					break;
				case 'bottom':
					$delta = $max_size - $height;
					if ($delta < '0') $delta = '0';
					$result .= ' margin-top: ' . $delta . 'px;';
					break;
				default:
					if (is_numeric($valign)) {
						$delta = $valign;
						$result .= ' margin-top: '.$delta.'px; margin-bottom: '.$delta.'px;';
					}
			}
			if ($wppa_opt['wppa_use_thumb_opacity'] && !is_feed()) {
				$opac = $wppa_opt['wppa_thumb_opacity'];
				$result .= ' opacity:' . $opac/100 . '; filter:alpha(opacity=' . $opac . ');';
			}
if ($type == 'thumb' && $wppa_opt['wppa_thumb_linktype'] != 'none') $result .= ' cursor:pointer;';
if ($type == 'ttthumb' && $wppa_opt['wppa_topten_widget_linktype'] != 'none') $result .= ' cursor:pointer;';
if ($type == 'fthumb') $result .= ' cursor:pointer;';
			break;
		case 'fullsize':
			$result .= ' width:' . $width . 'px;';
			
			if (!$wppa['auto_colwidth']) {
				$result .= 'height:' . $height . 'px;';
			}
			
			if ($wppa['is_slideonly'] == '1') {
				if ($wppa['ss_widget_valign'] != '') $valign = $wppa['ss_widget_valign'];
				else $valign = 'fit';
			}
			elseif ($xvalign == 'optional') {
				$valign = $wppa_opt['wppa_fullvalign'];
			}
			else {
				$valign = $xvalign;
			}
			
			if ($valign != 'default') {
				// Center horizontally
				$delta = round(($max_size - $width) / 2);
				if ($delta < '0') $delta = '0';
				$result .= ' margin-left:' . $delta . 'px;';
				// Position vertically
				$delta = '0';
				if (!$wppa['auto_colwidth'] && !wppa_page('oneofone')) {
					switch ($valign) {
						case 'top':
						case 'fit':
							$delta = '0';
							break;
						case 'center':
							$delta = round(($max_height - $height) / 2);
							if ($delta < '0') $delta = '0';
							break;
						case 'bottom':
							$delta = $max_height - $height;
							if ($delta < '0') $delta = '0';
							break;
					}
				}
				$result .= ' margin-top:' . $delta . 'px;';
			}
			break;
		default:
			$wppa['out'] .=  ('Error wrong "$type" argument: '.$type.' in wppa_get_imgstyle');
	}
	return $result;
}

function wppa_is_landscape($img_attr) {
	return ($img_attr[0] > $img_attr[1]);
}

function wppa_get_imgevents($type = '', $id = '', $no_popup = false) {
global $wppa;
global $wppa_opt;

	$result = '';
	$perc = '';
	if ($type == 'thumb') {
		if ($wppa_opt['wppa_use_thumb_opacity'] || $wppa_opt['wppa_use_thumb_popup']) {
			
			if ($wppa_opt['wppa_use_thumb_opacity']) {
				$perc = $wppa_opt['wppa_thumb_opacity'];
				$result = ' onmouseout="jQuery(this).fadeTo(400, ' . $perc/100 . ')" onmouseover="jQuery(this).fadeTo(400, 1.0);';
			} else {
				$result = ' onmouseover="';
			}
			if (!$no_popup && $wppa_opt['wppa_use_thumb_popup']) {
				$rating = wppa_get_rating_by_id($id);
				$result .= 'wppa_popup(' . $wppa['master_occur'] . ', this, ' . $id . ', \''.$rating.'\');';
			}
			$result .= '" ';
		}
	}
	elseif ($type == 'cover') {
		if ($wppa_opt['wppa_use_cover_opacity']) {
			$perc = $wppa_opt['wppa_cover_opacity'];
			$result = ' onmouseover="jQuery(this).fadeTo(400, 1.0)" onmouseout="jQuery(this).fadeTo(400, ' . $perc/100 . ')" ';
		}
	}		
	return $result;
}

function wppa_html($str) {
global $wppa_opt;

	if ($wppa_opt['wppa_html']) {
		$result = html_entity_decode($str);
	}
	else {
		$result = $str;
	}
	return $result;
}

function wppa_onpage($type = '', $counter, $curpage) {
global $wppa;

	if ($wppa['src']) return true;	//?
	$pagesize = wppa_get_pagesize($type);
	if ($pagesize == '0') {			// Pagination off
		if ($curpage == '1') return true;	
		else return false;
	}
	$cnt = $counter - 1;
	$crp = $curpage - 1;
	if (floor($cnt / $pagesize) == $crp) return true;
	return false;
}

function wppa_page_links($npages = '1', $curpage = '1') {
global $wppa;
	
	if ($npages < '2') return;	// Nothing to display
	if (is_feed()) {
//		wppa_dummy_bar(__('- - - Pagelinks - - -', 'wppa_theme'));
		return;
	}
	
	// Compose the Previous and Next Page urls
	if (isset($_GET['cover'])) $ic = $_GET['cover'];
	else {
		if ($wppa['is_cover'] == '1') $ic = '1'; else $ic = '0';
	}
	$pnu = wppa_get_permalink().'cover='.$ic;
	if (isset($_GET['album'])) $pnu .= '&amp;album=' . $_GET['album'];
	if (isset($_GET['photo'])) $pnu .= '&amp;photo=' . $_GET['photo'];
	$occur = $wppa['in_widget'] ? $wppa['widget_occur'] : $wppa['occur'];
	$w = $wppa['in_widget'] ? 'w' : '';
	$pnu .= '&amp;'.$w.'occur=' . $occur;
	$prevurl = $pnu . '&amp;page=' . ($curpage - 1);	
	$nexturl = $pnu . '&amp;page=' . ($curpage + 1);
	
	$from = 1;
	$to = $npages;
	if ($npages > '7') {
		$from = $curpage - '3';
		$to = $curpage + 3;
		while ($from < '1') {
			$from++;
			$to++;
		}
		while ($to > $npages) {
			$from--;
			$to--;
		}
	}

	$wppa['out'] .= '<div id="prevnext-a-'.$wppa['master_occur'].'" class="wppa-nav-text wppa-box wppa-nav" style="clear:both; text-align:center; '.__wcs('wppa-box').__wcs('wppa-nav').'" >';
		$vis = $curpage == '1' ? 'visibility: hidden;' : '';
		$wppa['out'] .= '<div id="prev-page" style="float:left; text-align:left; '.$vis.'">';
			$wppa['out'] .= '<span class="wppa-arrow" style="'.__wcs('wppa-arrow').'cursor: default;">&laquo;&nbsp;</span>';
			$wppa['out'] .= '<a id="p-p" href="'.$prevurl.'" >'.__a('Prev.&nbsp;page', 'wppa_theme').'</a>';
		$wppa['out'] .= '</div><!-- #prev-page -->';
		$vis = $curpage == $npages ? 'visibility: hidden;' : '';
		$wppa['out'] .= '<div id="next-page" style="float:right; text-align:right; '.$vis.'">';
			$wppa['out'] .= '<a id="n-p" href="'.$nexturl.'" >'.__a('Next&nbsp;page', 'wppa_theme').'</a>';
			$wppa['out'] .= '<span class="wppa-arrow" style="'.__wcs('wppa-arrow').'cursor: default;">&nbsp;&raquo;</span>';
		$wppa['out'] .= '</div><!-- #next-page -->';
		
		if ($from > '1') {
			$wppa['out'] .= ('.&nbsp;.&nbsp;.&nbsp;');
		}
		for ($i=$from; $i<=$to; $i++) {
			if ($curpage == $i) { 
				$wppa['out'] .= '<div class="wppa-mini-box wppa-alt wppa-black" style="display:inline; text-align:center; '.__wcs('wppa-mini-box').__wcs('wppa-alt').__wcs('wppa-black').' text-decoration: none; cursor: default; font-weight:normal; " >';
					$wppa['out'] .= '<a style="font-weight:normal; text-decoration: none; cursor: default; '.__wcs('wppa-black').'">&nbsp;'.$i.'&nbsp;</a>';
				$wppa['out'] .= '</div>';
			}
			else { 
				$wppa['out'] .= '<div class="wppa-mini-box wppa-even" style="display:inline; text-align:center; '.__wcs('wppa-mini-box').__wcs('wppa-even').'" >';
					$wppa['out'] .= '<a href="'.$pnu.'&amp;page='.$i.'">&nbsp;'.$i.'&nbsp;</a>';
				$wppa['out'] .= '</div>';	
			}
		}
		if ($to < $npages) {
			$wppa['out'] .= ('&nbsp;.&nbsp;.&nbsp;.');
		}
	$wppa['out'] .= '</div><!-- #prevnext-a-'.$wppa['master_occur'].' -->';
}
	
function wppa_set_runtimestyle() { 
global $wppa;
global $wppa_opt;

	if ($wppa_opt['wppa_rating_on']) {
		/* Nonce field for rating security */
		if (isset($_GET['rating'])) {
			if (isset($_GET['wppa_nonce'])) $nonce = $_GET['wppa_nonce']; else $nonce = '';
			$ok = wp_verify_nonce($nonce, 'wppa-check');
			if ($ok) sleep(2);
			else die(__('<b>ERROR: Illegal attempt to enter a rating.</b>', 'wppa_theme'));
		}
		$wppa['out'] .= wp_nonce_field('wppa-check' , 'wppa_nonce', false, false);
	}
	/* If you simplify this by making a cdata block, you will break rss feeds */
	/* rss attempts to create a nested cdata block that causes the loss of the script tag */
	/* The errormessage will say that the /script tag is not matched while it is. */
	
	/* This goes into wppa_theme.js */ 
	$wppa['out'] .= '<script type="text/javascript">';
	$wppa['out'] .= 'wppa_bgcolor_img = "'.$wppa_opt['wppa_bgcolor_img'].'";';
	if ($wppa_opt['wppa_thumb_linktype'] == 'none') $wppa['out'] .= 'wppa_popup_nolink = true;'; 
	else $wppa['out'] .= 'wppa_popup_nolink = false;';
	
	/* This goes into wppa_slideshow.js */ 
	if ($wppa_opt['wppa_fadein_after_fadeout']) $wppa['out'] .= 'wppa_fadein_after_fadeout = true;';
	else $wppa['out'] .= 'wppa_fadein_after_fadeout = false;';
	$wppa['out'] .= 'wppa_animation_speed = '.$wppa_opt['wppa_animation_speed'].';';
	$wppa['out'] .= 'wppa_imgdir = "'.wppa_get_imgdir().'";';
	if ($wppa['auto_colwidth']) $wppa['out'] .= 'wppa_auto_colwidth = true;';
	else $wppa['out'] .= 'wppa_auto_colwidth = false;';
	$wppa['out'] .= 'wppa_thumbnail_area_delta = '.wppa_get_thumbnail_area_delta().';';
	$wppa['out'] .= 'wppa_textframe_delta = '.wppa_get_textframe_delta().';';
	$wppa['out'] .= 'wppa_box_delta = '.wppa_get_box_delta().';';
	$wppa['out'] .= 'wppa_ss_timeout = '.$wppa_opt['wppa_slideshow_timeout'].';';
	$wppa['out'] .= 'wppa_preambule = '.wppa_get_preambule().';';
	$temp = $wppa_opt['wppa_tf_width'] + $wppa_opt['wppa_tn_margin'];
	$wppa['out'] .= 'wppa_thumbnail_pitch = '.$temp.';';
//	$temp = wppa_get_container_width() - ( 2*6 + 2*23 + 2*$wppa_opt['wppa_bwidth']);	// may be too early, see las minute changes in wppa_container()
//	$wppa['out'] .= 'wppa_filmstrip_length = '.$temp.';';								// "
	$temp = $wppa_opt['wppa_tn_margin'] / 2;
	$wppa['out'] .= 'wppa_filmstrip_margin = '.$temp.';';
	$temp = 2*6 + 2*23 + 2*$wppa_opt['wppa_bwidth'];
	$wppa['out'] .= 'wppa_filmstrip_area_delta = '.$temp.';';
	if ($wppa_opt['wppa_film_show_glue'] == 'yes') $wppa['out'] .= 'wppa_film_show_glue = true;';
	else $wppa['out'] .= 'wppa_film_show_glue = false;';
	$wppa['out'] .= 'wppa_slideshow = "'.__a('Slideshow', 'wppa_theme').'";';
	$wppa['out'] .= 'wppa_start = "'.__a('Start', 'wppa_theme').'";';
	$wppa['out'] .= 'wppa_stop = "'.__a('Stop', 'wppa_theme').'";';
	$wppa['out'] .= 'wppa_photo = "'.__a('Photo', 'wppa_theme').'";';
	$wppa['out'] .= 'wppa_of = "'.__a('of', 'wppa_theme').'";';
	$wppa['out'] .= 'wppa_prevphoto = "'.__a('Prev.&nbsp;photo', 'wppa_theme').'";';
	$wppa['out'] .= 'wppa_nextphoto = "'.__a('Next&nbsp;photo', 'wppa_theme').'";';
	$wppa['out'] .= 'wppa_username = "'.wppa_get_user().'";';
	if ($wppa_opt['wppa_rating_change'] || $wppa_opt['wppa_rating_multi']) $wppa['out'] .= 'wppa_rating_once = false;';
	else $wppa['out'] .= 'wppa_rating_once = true;';
	$wppa['out'] .= '</script>';
	
//	echo $wppa['out'];
	
//	$wppa['out'] = '';
}

function wppa_get_pagesize($type = '') {
global $wppa_opt;

	if (isset($_POST['wppa-searchstring'])) return '0';
	if ($type == 'albums') return $wppa_opt['wppa_album_page_size'];
	if ($type == 'thumbs') return $wppa_opt['wppa_thumb_page_size'];
	return '0';
}

function wppa_deep_stristr($string, $tokens) {
global $wppa_stree;
	// Explode tokens into search tree
	if (!isset($wppa_stree)) {
		// sanitize search token string
		$tokens = trim($tokens);
		while (strstr($tokens, ', ')) $tokens = str_replace(', ', ',', $tokens);
		while (strstr($tokens, ' ,')) $tokens = str_replace(' ,', ',', $tokens);
		while (strstr($tokens, '  ')) $tokens = str_replace('  ', ' ', $tokens);
		while (strstr($tokens, ',,')) $tokens = str_replace(',,', ',', $tokens);
		// to level explode
		if (strstr($tokens, ',')) {
			$wppa_stree = explode(',', $tokens);
		}
		else {
			$wppa_stree[0] = $tokens;
		}
		// bottom level explode
		for ($idx = 0; $idx < count($wppa_stree); $idx++) {
			if (strstr($wppa_stree[$idx], ' ')) {
				$wppa_stree[$idx] = explode(' ', $wppa_stree[$idx]);
			}
		}
	}
	// Check the search criteria
	foreach ($wppa_stree as $branch) {
		if (is_array($branch)) {
			if (wppa_and_stristr($string, $branch)) return true;
		}
		else {
			if (stristr($string, $branch)) return true;
		}
	}
	return false;
}

function wppa_and_stristr($string, $branch) {
	foreach ($branch as $leaf) {
		if (!stristr($string, $leaf)) return false;
	}
	return true;
}

function wppa_get_slide_frame_style() {
global $wppa;
global $wppa_opt;
	
	$fs = $wppa_opt['wppa_fullsize'];
	$cs = $wppa_opt['wppa_colwidth'];
	if ($cs == 'auto') {
		$cs = $fs;
		$wppa['auto_colwidth'] = true;
	}
	$result = '';
	$gfs = (is_numeric($wppa['fullsize']) && $wppa['fullsize'] > '0') ? $wppa['fullsize'] : $fs;
	
	$gfh = floor($gfs * $wppa_opt['wppa_maxheight'] / $wppa_opt['wppa_fullsize']);

//	if ($wppa['in_ss_widget'] && $wppa['portrait_only']) {
	if ($wppa['portrait_only']) {
		$result = 'width: ' . $gfs . 'px;';	// No height
	}
	else {
		if (wppa_page('oneofone')) {
			$imgattr = getimagesize(wppa_get_image_path_by_id($wppa['single_photo']));
			$h = floor($gfs * $imgattr[1] / $imgattr[0]);
			$result .= 'height: ' . $h . 'px;';
		}
		elseif ($wppa['auto_colwidth']) {
			$result .= ' height: ' . $gfh . 'px;';
		}
		elseif ($wppa['ss_widget_valign'] != '' && $wppa['ss_widget_valign'] != 'fit') {
			$result .= ' height: ' . $gfh . 'px;'; 
		}
		elseif ($wppa_opt['wppa_fullvalign'] == 'default') {
			$result .= 'min-height: ' . $gfh . 'px;'; 
		}
		else {
			$result .= 'height: ' . $gfh . 'px;'; 
		}
		$result .= 'width: ' . $gfs . 'px;';
	}
	
	$hor = $wppa_opt['wppa_fullhalign'];
	if ($gfs == $fs) {
		if ($fs != $cs) {
			switch ($hor) {
			case 'left':
				$result .= 'margin-left: 0px;';
				break;
			case 'center':
				$result .= 'margin-left: ' . floor(($cs - $fs) / 2) . 'px;';
				break;
			case 'right':
				$result .= 'margin-left: ' . ($cs - $fs) . 'px;';
				break;
			}
		}
	}

	return $result;
}

function wppa_get_thumb_frame_style($glue = false, $film = '') {
global $wppa_opt;

	$tfw = $wppa_opt['wppa_tf_width'];
	$tfh = $wppa_opt['wppa_tf_height'];
	$mgl = $wppa_opt['wppa_tn_margin'];
	$mgl2 = floor($mgl / '2');
	if ($film == '' && $wppa_opt['wppa_thumb_auto']) {
		$area = wppa_get_box_width() + $tfw;	// Area for n+1 thumbs
		$n_1 = floor($area / ($tfw + $mgl));
		$mgl = floor($area / $n_1) - $tfw;	
	}
	if (is_numeric($tfw) && is_numeric($tfh)) {
		$result = 'width: '.$tfw.'px; height: '.$tfh.'px; margin-left: '.$mgl.'px; margin-top: '.$mgl2.'px; margin-bottom: '.$mgl2.'px;';
		if ($glue && $wppa_opt['wppa_film_show_glue']) {
			$result .= 'padding-right:'.$mgl.'px; border-right: 2px dotted gray;';
		}
	}
	else $result = '';
	return $result;
}

function wppa_get_container_width() {
global $wppa;
global $wppa_opt;

	if (is_numeric($wppa['fullsize']) && $wppa['fullsize'] > '0') {
		$result = $wppa['fullsize'];
	}
	else {
		$result = $wppa_opt['wppa_colwidth'];
		if ($result == 'auto') {
			$result = '640';
			$wppa['auto_colwidth'] = true;
		}
	}
	return $result;
}

function wppa_get_thumbnail_area_width() {
	$result = wppa_get_container_width();
	$result -= wppa_get_thumbnail_area_delta();
	return $result;
}

function wppa_get_thumbnail_area_delta() {
global $wppa_opt;

	$result = 7 + 2 * $wppa_opt['wppa_bwidth'];	// 7 = .thumbnail_area padding-left
	return $result;
}

function wppa_get_container_style() {
global $wppa;
global $wppa_opt;

	$result = '';
	
	// See if there is space for a margin
	$marg = false;
	if (is_numeric($wppa['fullsize'])) {
		$cw = $wppa_opt['wppa_colwidth'];
		if (is_numeric($cw)) {
			if ($cw > ($wppa['fullsize'] + 10)) {
				$marg = '10px;';
			}
		}
	}
	
	if (!$wppa['in_widget']) $result .= 'clear: both; ';
	$ctw = wppa_get_container_width();
	if ($wppa['auto_colwidth']) {
		if (is_feed()) {
			$result .= 'width:'.$ctw.'px;';
		}
	}
	else {
		$result .= 'width:'.$ctw.'px;';
	}
	
//	if ($wppa['align'] == '' || 
	if ($wppa['align'] == 'left') {
		$result .= 'float: left;';
		if ($marg) $result .= 'margin-right: '.$marg;
	}
	elseif ($wppa['align'] == 'center') $result .= 'display: block; margin-left: auto; margin-right: auto;'; 
	elseif ($wppa['align'] == 'right') {
		$result .= 'float: right;';
		if ($marg) $result .= 'margin-left: '.$marg;
	}
	
	return $result;
}

function wppa_get_curpage() {
global $wppa;

	if (isset($_GET['page'])) {
		if ($wppa['in_widget']) {
			$oc = isset($_GET['woccur']) ? $_GET['woccur'] : '1';
			$curpage = $wppa['widget_occur'] == $oc ? $_GET['page'] : '1';
		}
		else {
			$oc = isset($_GET['occur']) ? $_GET['occur'] : '1';
			$curpage = $wppa['occur'] == $oc ? $_GET['page'] : '1';
		}
	}
	else $curpage = '1';
	return $curpage;
}

function wppa_container($action) {
global $wppa;	
global $wppa_opt;			
global $wppa_version;			// The theme version (wppa_theme.php)
global $wppa_alt;
global $wppa_microtime;
global $wppa_microtime_cum;

	$wppa_debug = false;		// reserved for future use

	if (is_feed()) return;		// Need no container in RSS feeds
	if ($action == 'open') {

		if ($wppa_debug) $wppa_microtime = microtime(true);
		
		if (wppa_page('oneofone')) $wppa['portrait_only'] = true;
		$wppa_alt = 'alt';

		if ($wppa['master_occur'] == '1') wppa_set_runtimestyle();
		
		// last minute change: script %%size=auto%% or reset
		// $wppa['auto_colwidth'] is set by the filter or by wppa_albums in case called directly
		// $wppa_opt['wppa_colwidth'] is the option setting
		// script or call has precedence over option setting
		// so: if set by script or call: auto, else if set by option: auto
		$auto = false;
		if ($wppa['auto_colwidth']) $auto = true;
		elseif ($wppa_opt['wppa_colwidth'] == 'auto') $auto = true;
		if ($auto) {
			$wppa['out'] .= '<script type="text/javascript">wppa_auto_colwidth = true;</script>';
		}
/*		
		if ($wppa_opt['wppa_colwidth'] == 'auto' && !$wppa['auto_colwidth']) {	
			$wppa['out'] .= '<script type="text/javascript">wppa_auto_colwidth = false;</script>';
		}
		if ($wppa_opt['wppa_colwidth'] != 'auto' && $wppa['auto_colwidth']) {	
			$wppa['out'] .= '<script type="text/javascript">wppa_auto_colwidth = true;</script>';
		}
*/
		// last minute change: script %%size != default colwidth
		$temp = wppa_get_container_width() - ( 2*6 + 2*23 + 2*$wppa_opt['wppa_bwidth']);
		$wppa['out'] .= '<script type="text/javascript">wppa_filmstrip_length['.$wppa['master_occur'].'] = '.$temp.';</script>'; // array van maken per mocc!!!!
		
		// Open the container
		$wppa['out'] .= ('<div id="wppa-container-'.$wppa['master_occur'].'" style="'.wppa_get_container_style().'" class="wppa-container wppa-rev-'.$wppa['revno'].' wppa-theme-'.$wppa_version.' wppa-api-'.$wppa['api_version'].'" >');
	}
	elseif ($action == 'close')	{
		if (wppa_page('oneofone')) $wppa['portrait_only'] = false;
		if (!$wppa['in_widget']) $wppa['out'] .= ('<div style="clear:both;"></div>');
		$wppa['out'] .= '</div>';
//		$wppa['out'] .= ('</div><!-- wppa-container-'.$wppa['master_occur'].' -->');
//		if (!$wppa['in_widget']) 
//						$wppa['out'] .= ('<p>');					// Re-open paragraph
						
		if ($wppa_debug) {
			$laptim = microtime(true) - $wppa_microtime;
			if (!is_numeric($wppa_microtime_cum)) $wppa_mcrotime_cum = '0';
			$wppa_microtime_cum += $laptim;
			echo('<span style="color:blue;"><small>Time elapsed occ '.$wppa['master_occur'].':'.substr($laptim, 0, 5).'s. Tot:'.substr($wppa_microtime_cum, 0, 5).'s.</small></span><br/>');
		}
	}
	else {
		$wppa['out'] .= ('<span style="color:red;">Error, wppa_container() called with wrong argument: '.$action.'. Possible values: \'open\' or \'close\'</span>');
	}
}

function wppa_album_list($action) {
global $wppa;
global $cover_count;

	if ($action == 'open') {
		$cover_count = '0';
		$wppa['out'] .= ('<div id="wppa-albumlist-'.$wppa['master_occur'].'" class="albumlist">');
	}
	elseif ($action == 'close') {
		$wppa['out'] .= ('</div><!-- wppa-albumlist-'.$wppa['master_occur'].' -->');
	}
	else {
		$wppa['out'] .= ('<span style="color:red;">Error, wppa_albumlist() called with wrong argument: '.$action.'. Possible values: \'open\' or \'close\'</span>');
	}
}

function wppa_thumb_list($action) {
global $wppa;
global $cover_count;

	if ($action == 'open') {
		$cover_count = '0';
		$wppa['out'] .= ('<div id="wppa-thumblist-'.$wppa['master_occur'].'" class="thumblist">');
	}
	elseif ($action == 'close') {
		$wppa['out'] .= ('</div><!-- wppa-thumblist-'.$wppa['master_occur'].' -->');
	}
	else {
		$wppa['out'] .= ('<span style="color:red;">Error, wppa_thumblist() called with wrong argument: '.$action.'. Possible values: \'open\' or \'close\'</span>');
	}
}

function wppa_thumb_area($action) {
global $wppa;
global $wppa_alt;

	if ($action == 'open') {
		if (is_feed()) {
			$wppa['out'] .= ('<div id="wppa-thumbarea-'.$wppa['master_occur'].'" style="clear: both: '.__wcs('wppa-box').__wcs('wppa-'.$wppa_alt).'">');
		}
		else {
			$wppa['out'] .= ('<div id="wppa-thumbarea-'.$wppa['master_occur'].'" style="clear: both; '.__wcs('wppa-box').__wcs('wppa-'.$wppa_alt).'width: '.wppa_get_thumbnail_area_width().'px;" class="thumbnail-area wppa-box wppa-'.$wppa_alt.'" onclick="wppa_popdown('.$wppa['master_occur'].')" >');
		}		
		if ($wppa_alt == 'even') $wppa_alt = 'alt'; else $wppa_alt = 'even';
	}
	elseif ($action == 'close') {
		$wppa['out'] .= ('</div><!-- wppa-thumbarea-'.$wppa['master_occur'].' -->');
	}
	else {
		$wppa['out'] .= ('<span style="color:red;">Error, wppa_thumb_area() called with wrong argument: '.$action.'. Possible values: \'open\' or \'close\'</span>');
	}
}

function wppa_get_npages($type, $array) {
global $wppa;
global $wppa_opt;

	$aps = wppa_get_pagesize('albums');	
	$tps = wppa_get_pagesize('thumbs'); 
	$result = '0';
	if ($type == 'albums') {
		if ($aps != '0') {
			$result = ceil(count($array) / $aps); 
		} 
		elseif ($tps != '0') {
			$result = '1'; 
		}
	}
	elseif ($type == 'thumbs') {
		if ($wppa['is_cover'] == '1') {		// Cover has no thumbs: 0 pages
			$result = '0';
		} 
		elseif ((count($array) <= $wppa_opt['wppa_min_thumbs']) && (!$wppa['src'])) {	// Less than treshold and not searching: 0
			$result = '0';
		}
		elseif ($tps != '0') {
			$result = ceil(count($array) / $tps);	// Pag on: compute
		}
		else {
			$result = '1';								// Pag off: all fits on 1
		}
	}
	return $result;
}

function wppa_album_cover() {
global $album;
global $wppa;
global $wppa_opt;
global $wppa_alt;
global $cover_count;

	$coverphoto = wppa_get_coverphoto_id();
	$photocount = wppa_get_photo_count();
	$albumcount = wppa_get_album_count();
	$mincount = wppa_get_mincount();
	$href = '';
	$title = '';
	// See if there is substantial content to the album
	$has_content = ($albumcount > '0') || ($photocount > $mincount);
	// Is there a page the album should point to?
	$linkpage = '';
	if ($album['cover_linkpage'] > 0) {	// a page is given
		$page_data = get_page($album['cover_linkpage']);
		if (!empty($page_data) && $page_data->post_status == 'publish') {
			// Yes a page is asked for and it exists
			if ($has_content) {	// make url with querystring for content
				$linkpage = $album['cover_linkpage'];
				$href = wppa_get_album_url($album['id'], $linkpage);
			}
			else {				// make plain link url
				$href = get_page_link($album['cover_linkpage']);
			}
			$title = __a('Link to', 'wppa_theme');
			$title .= ' ' . $page_data->post_title;
		} else {
			$href = '#';
			$title = __a('Page is not available.', 'wppa_theme');
		}
	} elseif ($album['cover_linkpage'] == -1) {	// no link at all
	} else {						// link to the same page/post
		if ($has_content) {				// The very most normal situation
			$href = wppa_get_album_url($album['id'], $linkpage); 
			$title = __a('View the album', 'wppa_theme').' '.wppa_qtrans(stripslashes($album['name']));
		}
		else {
			if ($photocount > '0') {	// coverphotos only
				$href = wppa_get_image_page_url_by_id($coverphoto); 
				if ($photocount == '1') $title = __a('View the cover photo', 'wppa_theme'); 
				else $title = __a('View the cover photos', 'wppa_theme');
			}
			else {						// nothing at all
				$href = '';
				$title = '';
			}
		}
	}
	// Find the coverphoto link
	$photolink = wppa_get_imglnk_a('coverimg', $coverphoto, $href, $title);
	// Find the coverphoto details
	$src = wppa_get_thumb_url_by_id($coverphoto);	
	$path = wppa_get_thumb_path_by_id($coverphoto);
	$imgattr = wppa_get_imgstyle($path, $wppa_opt['wppa_smallsize'], '', 'cover');
	if (is_feed()) {
		$events = '';
	}
	else {
		$events = wppa_get_imgevents('cover');
	}
	$photo_left = $wppa_opt['wppa_coverphoto_left'];
	
	$style =  __wcs('wppa-box').__wcs('wppa-'.$wppa_alt);
	if (is_feed()) $style .= ' padding:7px;';
	
	$wid = wppa_get_cover_width('cover');
	$style .= 'width: '.$wid.'px;';	
//	if ($cover_count != '0') $style .= 'margin-left: 8px;';
	if ($cover_count == '0') {
		$style .= 'clear:both;';
//$wppa['out'] .= '<hr style="height:0;" />';
	}
	else {
		$style .= 'margin-left: 8px;';
	}
	wppa_step_covercount('cover');
	
	$wppa['out'] .= '<div id="album-'.$album['id'].'-'.$wppa['master_occur'].'" class="album wppa-box wppa-cover-box wppa-'.$wppa_alt.'" style="'.$style.'" >';
		if ($src != '') { 
			$photoframestyle = $photo_left ? 'style="float:left; margin-right:5px;"' : 'style="float:right; margin-left:5px;"';
			$wppa['out'] .= '<div id="coverphoto_frame_'.$album['id'].'_'.$wppa['master_occur'].'" class="coverphoto-frame" '.$photoframestyle.'>';
			if ($photolink) {
				$wppa['out'] .= '<a href="'.$photolink['url'].'" title="'.$photolink['title'].'">';
					$wppa['out'] .= '<img src="'.$src.'" alt="'.$title.'" class="image wppa-img" style="'.__wcs('wppa-img').$imgattr.'" '.$events.'/>';
				$wppa['out'] .= '</a>'; 
			} else { 
				$wppa['out'] .= '<img src="'.$src.'" alt="'.$title.'" class="image wppa-img" style="'.__wcs('wppa-img').$imgattr.'" '.$events.'/>';
			} 
			$wppa['out'] .= '</div><!-- #coverphoto_frame_'.$album['id'].'_'.$wppa['master_occur'].' -->'; 
		} 
		$textframestyle = wppa_get_text_frame_style($photo_left, 'cover');
		$wppa['out'] .= '<div id="covertext_frame_'.$album['id'].'_'.$wppa['master_occur'].'" class="wppa-text-frame covertext-frame" '.$textframestyle.'>';
			$wppa['out'] .= '<h2 class="wppa-title" style="clear:none; '.__wcs('wppa-title').'">';
				if ($href != '') { 
					$wppa['out'] .= '<a href="'.$href.'" title="'.$title.'" class="wppa-title" style="'.__wcs('wppa-title').'">'.wppa_qtrans(stripslashes($album['name'])).'</a>';
				} else { 
					$wppa['out'] .= wppa_qtrans(stripslashes($album['name'])); 
				} 
			$wppa['out'] .= '</h2>';
			$wppa['out'] .= '<p class="wppa-box-text wppa-black" style="'.__wcs('wppa-box-text').__wcs('wppa-black').'">'.wppa_html(wppa_get_the_album_desc()).'</p>';
			$wppa['out'] .= '<div class="wppa-box-text wppa-black wppa-info wppa-slideshow-browse-link">';
				if ($photocount > $mincount) { 
					$label = $wppa_opt['wppa_enable_slideshow'] ?  __a('Slideshow', 'wppa_theme') : __a('Browse photos', 'wppa_theme');
					$wppa['out'] .= '<a href="'.wppa_get_slideshow_url($linkpage).'" title="'.$label.'" style="'.__wcs('wppa-box-text', 'nocolor').'" >'.$label.'</a>';
				} else $wppa['out'] .= '&nbsp;'; 
			$wppa['out'] .= '</div>';
			$wppa['out'] .= '<div class="wppa-box-text wppa-black wppa-info">';
				if ($has_content) {
					if ($wppa_opt['wppa_thumbtype'] == 'none') $photocount = '0'; 	// Fake photocount to prevent link to empty page
					if ($photocount > $mincount || $albumcount) {					// Stll has content
						$wppa['out'] .= '<a href="'.wppa_get_album_url($album['id'], $linkpage).'" title="'.__a('View the album', 'wppa_theme').' '.stripslashes(wppa_qtrans($album['name'])).'" style="'.__wcs('wppa-box-text', 'nocolor').'" >';
						$wppa['out'] .= __a('View', 'wppa_theme');
						if ($albumcount) { 
							if ($albumcount == '1') {
								$wppa['out'] .= ' 1 '.__a('album', 'wppa_theme'); 
							}
							else {
								$wppa['out'] .= ' '.$albumcount.' '.__a('albums', 'wppa_theme');
							}
						}
						if ($photocount > $mincount && $albumcount) {
							$wppa['out'] .= ' '.__a('and', 'wppa_theme'); 
						}
						if ($photocount > $mincount) { 
							if ($photocount == '1') {
								$wppa['out'] .= ' 1 '.__a('photo', 'wppa_theme');
							}
							else {
								$wppa['out'] .= ' '.$photocount.' '.__a('photos', 'wppa_theme'); 
							}
						} 
						$wppa['out'] .= '</a>'; 
					}
				} 
			$wppa['out'] .= '</div>';
		$wppa['out'] .= '</div>';
		$wppa['out'] .= '<div style="clear:both;"></div>';	
//$wppa['out'] .= '<hr/>';	
	$wppa['out'] .= '</div><!-- #album-'.$album['id'].'-'.$wppa['master_occur'].' -->';
	if ($wppa_alt == 'even') $wppa_alt = 'alt'; else $wppa_alt = 'even';
}

function wppa_thumb_ascover() {
global $thumb;
global $wppa;
global $wppa_opt;
global $wppa_alt;
global $cover_count;

	$path = wppa_get_thumb_path(); 
	$imgattr = wppa_get_imgstyle($path, $wppa_opt['wppa_smallsize'], '', 'cover'); 
	$events = is_feed() ? '' : wppa_get_imgevents('cover'); 
	$src = wppa_get_thumb_url(); 
	$link = wppa_get_imglnk_a('thumb', $thumb['id']);
	if ($link) {
		$href = $link['url'];
		$title = $link['title'];
	}
	else {
		$href = '';
		$title = '';
	}
//	$title = esc_js(wppa_get_photo_name($thumb['id'])); 
//	$href = w ppa_get_photo_page_url('thumb');

	$photo_left = $wppa_opt['wppa_thumbphoto_left'];
	
	$style = __wcs('wppa-box').__wcs('wppa-'.$wppa_alt);
	if (is_feed()) $style .= ' padding:7px;';
	
	$wid = wppa_get_cover_width('thumb');
	$style .= 'width: '.$wid.'px;';	
//	if ($cover_count != '0') $style .= 'margin-left: 8px;';
	if ($cover_count == '0') {
		$style .= 'clear:both;';
	}
	else {
		$style .= 'margin-left: 8px;';
	}
	wppa_step_covercount('thumb');

	$wppa['out'] .= '<div id="thumb-'.$thumb['id'].'-'.$wppa['master_occur'].'" class="thumb wppa-box wppa-cover-box wppa-'.$wppa_alt.'" style="'.$style.'" >';
		if ($src != '') { 
			$photoframestyle = $photo_left ? 'style="float:left; margin-right:5px;"' : 'style="float:right; margin-left:5px;"';
			$wppa['out'] .= '<div id="thumbphoto_frame_'.$thumb['id'].'_'.$wppa['master_occur'].'" class="thumbphoto-frame" '.$photoframestyle.'>';
				$wppa['out'] .= '<a href="'.$href.'" title="'.$title.'">';
					$wppa['out'] .= '<img src="'.$src.'" alt="'.$title.'" class="image wppa-img" style="'.__wcs('wppa-img').$imgattr.'" '.$events.'/>';
				$wppa['out'] .= '</a>';
			$wppa['out'] .= '</div>';
		}
		$textframestyle = wppa_get_text_frame_style($photo_left, 'thumb');
		$wppa['out'] .= '<div id="thumbtext_frame_'.$thumb['id'].'_'.$wppa['master_occur'].'" class="wppa-text-frame thumbtext-frame" '.$textframestyle.'>';
			$wppa['out'] .= '<h2 class="wppa-title" style="clear:none;">';
				$wppa['out'] .= '<a href="'.$href.'" title="'.$title.'" style="'.__wcs('wppa-title').'" >'.wppa_qtrans(stripslashes($thumb['name'])).'</a>';
			$wppa['out'] .= '</h2>';
			$wppa['out'] .= '<p class="wppa-box-text wppa-black" style="'.__wcs('wppa-box-text').__wcs('wppa-black').'" >'.wppa_html(wppa_qtrans(stripslashes($thumb['description']))).'</p>';
		$wppa['out'] .= '</div>';
		$wppa['out'] .= '<div style="clear:both;"></div>';
	$wppa['out'] .= '</div><!-- thumb-'.$thumb['id'].'-'.$wppa['master_occur'].' -->';
	if ($wppa_alt == 'even') $wppa_alt = 'alt'; else $wppa_alt = 'even';
}

function wppa_thumb_default() {
global $thumb;
global $wppa;
global $wppa_opt;

	$src = wppa_get_thumb_path(); 
	$imgattr = wppa_get_imgstyle($src, $wppa_opt['wppa_thumbsize'], 'optional', 'thumb'); 
	$url = wppa_get_thumb_url(); 
	$events = wppa_get_imgevents('thumb', $thumb['id']); 
	$thumbname = esc_attr(wppa_qtrans($thumb['name']));

	if ($wppa_opt['wppa_use_thumb_popup'] == 'yes') $title = esc_attr(wppa_qtrans(stripslashes($thumb['description'])));
	else $title = esc_js(wppa_get_photo_name($thumb['id']));
	
	if (is_feed()) {
		$wppa['out'] .= '<a href="'.get_permalink().'><img src="'.$url.'" alt="'.$thumbname.'" title="'.$thumbname.'" style="'.wppa_get_imgstyle($src, '100', '4', 'thumb').'" /></a>';
		return;
	}
	$wppa['out'] .= '<div id="thumbnail_frame_'.$thumb['id'].'_'.$wppa['master_occur'].'" class="thumbnail-frame" style="'.wppa_get_thumb_frame_style().'" >';

		$link = wppa_get_imglnk_a('thumb', $thumb['id']);
		if ($link) {
			$wppa['out'] .= '<a href="'.$link['url'].'" class="thumb-img" id="x-'.$thumb['id'].'-'.$wppa['master_occur'].'">';
				$wppa['out'] .= '<img src="'.$url.'" alt="'.$thumbname.'" title="'.esc_attr($title).'" style="'.$imgattr.'" '.$events.' />';
			$wppa['out'] .= '</a>';
		}
		else {
			if ($wppa_opt['wppa_use_thumb_popup']) {
				$wppa['out'] .= '<div id="x-'.$thumb['id'].'-'.$wppa['master_occur'].'">';
					$wppa['out'] .= '<img src="'.$url.'" alt="'.$thumbname.'" title="'.esc_attr($title).'" style="'.$imgattr.'" '.$events.' />';
				$wppa['out'] .= '</div>';
			}
			else {
				$wppa['out'] .= '<img src="'.$url.'" alt="'.$thumbname.'" title="'.esc_attr($title).'" style="'.$imgattr.'" '.$events.' />';
			}
		}
		
		if ($wppa['src'] || isset($_GET['topten'])) { 
			$wppa['out'] .= '<div class="wppa-thumb-text" style="'.__wcs('wppa-thumb-text').'" >(<a href="'.wppa_get_album_url($thumb['album']).'">'.stripslashes(wppa_get_album_name($thumb['album'])).'</a>)</div>';
		}
		if ($wppa_opt['wppa_thumb_text_name']) {
			$wppa['out'] .= '<div class="wppa-thumb-text" style="'.__wcs('wppa-thumb-text').'" >'.wppa_qtrans(stripslashes($thumb['name'])).'</div>';
		}
		if ($wppa_opt['wppa_thumb_text_desc']) {
			$wppa['out'] .= '<div class="wppa-thumb-text" style="'.__wcs('wppa-thumb-text').'" >'.wppa_html(wppa_qtrans(stripslashes($thumb['description']))).'</div>';
		}
		if ($wppa_opt['wppa_thumb_text_rating']) {
			$wppa['out'] .= '<div class="wppa-thumb-text" style="'.__wcs('wppa-thumb-text').'" >'.wppa_get_rating_by_id($thumb['id']).'</div>';
		}
	$wppa['out'] .= '</div><!-- #thumbnail_frame_'.$thumb['id'].'_'.$wppa['master_occur'].' -->';
}	

function wppa_get_mincount() {
global $wppa;
global $wppa_opt;

	$result = $wppa['src'] ? '0' : $wppa_opt['wppa_min_thumbs'];	// Showing thumbs as searchresult has no minimum
	return $result;
}

function wppa_slide_frame() {
global $wppa;

	if (is_feed()) {
		if (wppa_page('oneofone')) {
//			wppa_dummy_bar(__('- - - Single photo - - -', 'wppa_theme'));
		}
		else {
//			wppa_dummy_bar(__('- - - Slideshow - - -', 'wppa_theme'));
		}
		return;
	}
	$wppa['out'] .= '<div id="slide_frame-'.$wppa['master_occur'].'" class="slide-frame" style="'.wppa_get_slide_frame_style().'">';
		$wppa['out'] .= '<div id="theslide0-'.$wppa['master_occur'].'" class="theslide"></div>';
		$wppa['out'] .= '<div id="theslide1-'.$wppa['master_occur'].'" class="theslide"></div>';
		$wppa['out'] .= '<div id="spinner-'.$wppa['master_occur'].'" class="spinner"></div>';
	$wppa['out'] .= '</div>';
}

function wppa_startstop($opt = '') {
global $wppa;
global $wppa_opt;

	if (is_feed()) {
//		wppa_dummy_bar(__('- - - Start/stop slideshow navigation bar - - -', 'wppa_theme'));
		return;
	}
	if (($opt == 'optional') && !$wppa_opt['wppa_show_startstop_navigation']) return;
	if ($wppa['is_slideonly'] == '1') return;	/* Not when slideonly */
	
	$hide = $wppa_opt['wppa_enable_slideshow'] ? '' : 'display:none; ';

	$wppa['out'] .= '<div id="prevnext1-'.$wppa['master_occur'].'" class="wppa-box wppa-nav wppa-nav-text" style="text-align: center; '.__wcs('wppa-box').__wcs('wppa-nav').__wcs('wppa-nav-text').$hide.'">';
		$wppa['out'] .= '<a id="speed0-'.$wppa['master_occur'].'" class="wppa-nav-text speed0" style="'.__wcs('wppa-nav-text').'" onclick="wppa_speed('.$wppa['master_occur'].', false)">'.__a('Slower', 'wppa_theme').'</a> | ';
		$wppa['out'] .= '<a id="startstop-'.$wppa['master_occur'].'" class="wppa-nav-text startstop" style="'.__wcs('wppa-nav-text').'" onclick="wppa_startstop('.$wppa['master_occur'].', -1)">'.__a('Start', 'wppa_theme').'</a> | ';
		$wppa['out'] .= '<a id="speed1-'.$wppa['master_occur'].'" class="wppa-nav-text speed1" style="'.__wcs('wppa-nav-text').'" onclick="wppa_speed('.$wppa['master_occur'].', true)">'.__a('Faster', 'wppa_theme').'</a>';
	$wppa['out'] .= '</div><!-- #prevnext1 -->';
}

function wppa_browsebar($opt = '') {
global $wppa;
global $wppa_opt;

	if (is_feed()) {
//		wppa_dummy_bar(__('- - - Browse navigation bar - - -', 'wppa_theme'));
		return;
	}
	if (($opt == 'optional') && !$wppa_opt['wppa_show_browse_navigation']) return;
	if ($wppa['is_slideonly'] == '1') return;	/* Not when slideonly */

	$wppa['out'] .= '<div id="prevnext2-'.$wppa['master_occur'].'" class="wppa-box wppa-nav wppa-nav-text" style="text-align: center; '.__wcs('wppa-box').__wcs('wppa-nav').__wcs('wppa-nav-text').'">';
		$wppa['out'] .= '<span id="p-a-'.$wppa['master_occur'].'" class="wppa-nav-text wppa-arrow" style="float:left; text-align:left; '.__wcs('wppa-nav-text').__wcs('wppa-arrow').'">&laquo;&nbsp;</span>';
		$wppa['out'] .= '<a id="prev-arrow-'.$wppa['master_occur'].'" class="wppa-nav-text arrow-'.$wppa['master_occur'].'" style="float:left; text-align:left; cursor:pointer; '.__wcs('wppa-nav-text').'" onclick="wppa_prev('.$wppa['master_occur'].')" ></a>';
		$wppa['out'] .= '<span id="n-a-'.$wppa['master_occur'].'" class="wppa-nav-text wppa-arrow" style="float:right; text-align:right; '.__wcs('wppa-nav-text').__wcs('wppa-arrow').'">&nbsp;&raquo;</span>';
		$wppa['out'] .= '<a id="next-arrow-'.$wppa['master_occur'].'" class="wppa-nav-text arrow-'.$wppa['master_occur'].'" style="float:right; text-align:right; cursor:pointer; '.__wcs('wppa-nav-text').'" onclick="wppa_next('.$wppa['master_occur'].')"></a>';
		$wppa['out'] .= '<span id="counter-'.$wppa['master_occur'].'" class="wppa-nav-text wppa-black" style="text-align:center; '.__wcs('wppa-nav-text').'"></span>';
	$wppa['out'] .= '</div><!-- #prevnext2 -->';
}

function wppa_slide_description($opt = '') {
global $wppa;
global $wppa_opt;

	if (($opt == 'optional') && !$wppa_opt['wppa_show_full_desc']) return;
	if ($wppa['is_slideonly'] == '1') return;	/* Not when slideonly */
	$wppa['out'] .= '<p id="imagedesc-'.$wppa['master_occur'].'" class="wppa-fulldesc imagedesc" style="'.__wcs('wppa-fulldesc').'"></p>';
}

function wppa_slide_name($opt = '') {
global $wppa;
global $wppa_opt;

	if (($opt == 'optional') && !$wppa_opt['wppa_show_full_name']) return;
	if ($wppa['is_slideonly'] == '1') return;	/* Not when slideonly */
	$wppa['out'] .= '<p id="imagetitle-'.$wppa['master_occur'].'" class="wppa-fulltitle imagetitle" style="'.__wcs('wppa-fulltitle').'"></p>';
}	

function wppa_popup() {
global $wppa;

	$wppa['out'] .= '<div id="wppa-popup-'.$wppa['master_occur'].'" class="wppa-popup-frame wppa-thumb-text" style="'.__wcs('wppa-thumb-text').'" ></div>';
	$wppa['out'] .= '<div style="clear:both;"></div>';
}

function wppa_run_slidecontainer($type = '') {
global $wppa;
global $wppa_opt;

	if ($type == 'single') {
		if (is_feed()) {
			$wppa['out'] .= '<a href="'.get_permalink().'"><img src="'.wppa_get_image_url_by_id($wppa['single_photo']).'" style="'.wppa_get_fullimgstyle($wppa['single_photo']).'"/></a>';
			return;
		} else {
			$wppa['out'] .= '<script type="text/javascript">wppa_store_slideinfo('.wppa_get_slide_info(0, $wppa['single_photo']).');</script>';
			$wppa['out'] .= '<script type="text/javascript">wppa_fullvalign_fit['.$wppa['master_occur'].'] = true;</script>';
			$wppa['out'] .= '<script type="text/javascript">wppa_startstop('.$wppa['master_occur'].', 0);</script>';
		}
	}
	elseif ($type == 'slideshow') {
		$index = 0;
		$startindex = -1;
//		$first = -1;

		if (isset($_GET['photo'])) $startid = $_GET['photo'];	// Still slideshow at photo id $startid
		else {
			if ($wppa_opt['wppa_start_slide'] && $wppa_opt['wppa_enable_slideshow']) {
				$startid = -1;					// Start running
			}
			else $startid = -2;					// Start still at first photo
		}
		if (isset($_GET['album'])) $alb = $_GET['album'];
		else $alb = '';	// Album id is in $wppa['start_album']
		$thumbs = wppa_get_thumbs($alb);
		foreach ($thumbs as $tt) : $id = $tt['id'];
			$wppa['out'] .=  '<script type="text/javascript">wppa_store_slideinfo(' . wppa_get_slide_info($index, $id) . ');</script>';
//			if ($index == 0) $first = $id;
			if ($startid == -2) $startid = $id;
			if ($startid == $id) $startindex = $index;
			$index++;
		endforeach;
//		if ($startindex != -1) $first = $startid;
		
		if ($wppa['is_slideonly']) $startindex = -1;	// Start running, overrules everything
	
		if ($wppa['ss_widget_valign'] != '' && $wppa['ss_widget_valign'] != 'fit') {
		}
		elseif ($wppa_opt['wppa_fullvalign'] == 'fit' || $wppa['is_slideonly'] == '1' ) { 
			$wppa['out'] .= '<script type="text/javascript" >wppa_fullvalign_fit['.$wppa['master_occur'].'] = true;</script>';
		}
		
		if ($wppa['portrait_only']) {
			$wppa['out'] .= '<script type="text/javascript" >wppa_portrait_only['.$wppa['master_occur'].'] = true;</script>';
		}
		$wppa['out'] .= '<script type="text/javascript">wppa_startstop('.$wppa['master_occur'].', '.$startindex.');</script>';
	}
	else {
		$wppa['out'] .= '<span style="color:red;">Error, wppa_run_slidecontainer() called with wrong argument: '.$type.'. Possible values: \'single\' or \'slideshow\'</span>';
	}
}

function wppa_is_pagination() {
global $wppa;

	if ((wppa_get_pagesize('albums') == '0' && wppa_get_pagesize('thumbs') == '0') || $wppa['src']) return false;
	else return true;
}

// Custom box			// Reserved for future use
function wppa_slide_custom($opt = '') {
}

// Show Filmstrip	
function wppa_slide_filmstrip($opt = '') {
global $wppa;
global $wppa_opt;
global $thumb;

	$do_it = false;												// Init
	if (is_feed()) $do_it = true;								// feed -> do it to indicate that there is a slideshow
	else {														// Not a feed
		if ($opt != 'optional') $do_it = true;						// not optional -> do it
		else {														// optional
			if ($wppa_opt['wppa_filmstrip']) {							// optional and option on
				if (!$wppa['is_slideonly']) $do_it = true;					// always except slideonly
			}
			else {														// optional and option off
				if ($wppa['film_on']) $do_it = true;						// explicitly turned on
			}
		}
	}
	if (!$do_it) return;										// Don't do it
	
	if (isset($_GET['album'])) $alb = $_GET['album'];
	else $alb = '';	// Album id is in $wppa['start_album']
	$thumbs = wppa_get_thumbs($alb);
	if (!$thumbs || count($thumbs) < 1) return;
	
	$preambule = wppa_get_preambule();
		
	$width = ($wppa_opt['wppa_tf_width'] + $wppa_opt['wppa_tn_margin']) * (count($thumbs) + 2 * $preambule);
	$width += $wppa_opt['wppa_tn_margin'] + 2;
	$topmarg = $wppa_opt['wppa_thumbsize'] / 2 - 12 + 7;

	$w = wppa_get_container_width() - ( 2*6 + 2*23 + 2*$wppa_opt['wppa_bwidth']); /* 2*padding + 2*arrow + 2*border */
	$IE6 = 'width: '.$w.'px;';
	
	if (is_feed()) {
		$wppa['out'] .= '<div style="'.__wcs('wppa-box').__wcs('wppa-nav').'">';
	} 
	else {

	$wppa['out'] .= '<div class="wppa-box wppa-nav" style="'.__wcs('wppa-box').__wcs('wppa-nav').'height:'.($wppa_opt['wppa_thumbsize']+$wppa_opt['wppa_tn_margin']).'px;">';
		$wppa['out'] .= '<div style="float:left; text-align:left; cursor:pointer; margin-top:'.$topmarg.'px; width: 23px; font-size: 24px;"><a class="wppa-arrow" style="'.__wcs('wppa-arrow').'" id="prev-film-arrow-'.$wppa['master_occur'].'" onclick="wppa_prev('.$wppa['master_occur'].');" >&laquo;</a></div>';
		$wppa['out'] .= '<div style="float:right; text-align:right; cursor:pointer; margin-top:'.$topmarg.'px; width: 23px; font-size: 24px;"><a class="wppa-arrow" style="'.__wcs('wppa-arrow').'" id="next-film-arrow-'.$wppa['master_occur'].'" onclick="wppa_next('.$wppa['master_occur'].');">&raquo;</a></div>';
		$wppa['out'] .= '<div class="filmwindow" style="'.$IE6.' display: block; height:'.($wppa_opt['wppa_thumbsize']+$wppa_opt['wppa_tn_margin']).'px; margin: 0 20px 0 20px; overflow:hidden;">';
			$wppa['out'] .= '<div id="wppa-filmstrip-'.$wppa['master_occur'].'" style="height:'.$wppa_opt['wppa_thumbsize'].'px; width:'.$width.'px; margin-left: -100px;">';
	}
	
	$cnt = count($thumbs);
	$start = $cnt - $preambule;
	$end = $cnt;
	$idx = $start;
	while ($idx < $end) {
		$glue = $cnt == ($idx + 1) ? true : false;
		$ix = $idx;
		while ($ix < 0) $ix += $cnt;
		$thumb = $thumbs[$ix];
		wppa_do_filmthumb($ix, false, $glue);
		$idx++;
	}
	$idx = 0;
	foreach ($thumbs as $tt) : $thumb = $tt;
		$glue = $cnt == ($idx + 1) ? true : false;
		wppa_do_filmthumb($idx, true, $glue);
		$idx++;
	endforeach;
	$start = '0';
	$end = $preambule;
	$idx = $start;
	while ($idx < $end) {
		$ix = $idx;
		while ($ix >= $cnt) $ix -= $cnt;
		$thumb = $thumbs[$ix];
		wppa_do_filmthumb($ix, false);
		$idx++;
	}
	
	if (is_feed()) {
		$wppa['out'] .= '</div>';
	}
	else {
			$wppa['out'] .= '</div>';
		$wppa['out'] .= '</div>';
	$wppa['out'] .= '</div>';
	}
}

function wppa_do_filmthumb($idx, $do_for_feed = false, $glue = false) {
global $wppa;
global $wppa_opt;
global $thumb;

	$src = wppa_get_thumb_path(); 
	$imgattr = wppa_get_imgstyle($src, $wppa_opt['wppa_thumbsize'], 'optional', 'fthumb'); 
	$url = wppa_get_thumb_url(); 
	$events = wppa_get_imgevents('thumb', $thumb['id'], 'nopopup'); 
	$events .= ' onclick="wppa_goto('.$wppa['master_occur'].', '.$idx.')"';
	$thumbname = esc_attr(wppa_qtrans($thumb['name']));
	$title = $thumbname;
	if ($wppa_opt['wppa_enable_slideshow']) {
		$events .= ' ondblclick="wppa_startstop('.$wppa['master_occur'].', -1)"';
		$title = esc_attr(__a('Double click to start/stop slideshow running', 'wppa_theme'));
	}
	
	if (is_feed()) {
		if ($do_for_feed) {
			$wppa['out'] .= '<a href="'.get_permalink().'"><img src="'.$url.'" alt="'.$thumbname.'" title="'.$thumbname.'" style="'.wppa_get_imgstyle($src, '100', '4', 'thumb').'"/></a>';
		}
	} else {
	// If !$do_for_feed: pre-or post-ambule. To avoid dup id change it in that case
	$tmp = $do_for_feed ? 'film' : 'pre';
	$wppa['out'] .= '<div id="'.$tmp.'_thumbnail_frame_'.$thumb['id'].'_'.$wppa['master_occur'].'" class="thumbnail-frame" style="'.wppa_get_thumb_frame_style($glue, 'film').'" >';
		$wppa['out'] .= '<img src="'.$url.'" alt="'.$thumbname.'" title="'.$title.'" style="'.$imgattr.'" '.$events.'/>';
	$wppa['out'] .= '</div><!-- #thumbnail_frame_'.$thumb['id'].'_'.$wppa['master_occur'].' -->';
	}
}

function wppa_get_preambule() {
global $wppa_opt;

	$result = is_numeric($wppa_opt['wppa_colwidth']) ? $wppa_opt['wppa_colwidth'] : $wppa_opt['wppa_fullsize'];
	$result = ceil(ceil($result / $wppa_opt['wppa_thumbsize']) / 2 );
	return $result;
}

function __wcs($class = '', $nocolor = '') {
global $wppa_opt;

	$opt = '';
	$result = '';
	switch ($class) {
		case 'wppa-box':
			$opt = $wppa_opt['wppa_bwidth'];
			if ($opt > '0') $result .= 'border-style: solid; border-width:'.$opt.'px; ';
			$opt = $wppa_opt['wppa_bradius'];
			if ($opt > '0') {
			/*	$result .= 'border-radius:'.$opt.'px; ';	*/ /* Reserved for css3 */
				$result .= '-moz-border-radius:'.$opt.'px; -khtml-border-radius:'.$opt.'px; -webkit-border-radius:'.$opt.'px; ';
			}
			break;
		case 'wppa-mini-box':
			$opt = $wppa_opt['wppa_bwidth'];
			if ($opt > '0') {
				$opt = floor(($opt + 2) / 3);
				$result .= 'border-style: solid; border-width:'.$opt.'px; ';
			}
			$opt = $wppa_opt['wppa_bradius'];
			if ($opt > '0') {
				$opt = floor(($opt + 2) / 3);
			/*	$result .= 'border-radius:'.$opt.'px; ';	*/ /* Reserved for css3 */
				$result .= '-moz-border-radius:'.$opt.'px; -khtml-border-radius:'.$opt.'px; -webkit-border-radius:'.$opt.'px; ';
			}
			break;
		case 'wppa-thumb-text':
			$opt = $wppa_opt['wppa_fontfamily_thumb'];
			if ($opt != '') $result .= 'font-family:'.$opt.'; ';
			$opt = $wppa_opt['wppa_fontsize_thumb'];
			if ($opt != '') {
				$ls = floor($opt * 1.29);
				$result .= 'font-size:'.$opt.'px; line-height:'.$ls.'px; ';
			}
			$opt = $wppa_opt['wppa_fontcolor_thumb'];
			if ($opt != '') $result .= 'color:'.$opt.'; ';
			break;
		case 'wppa-box-text':
			$opt = $wppa_opt['wppa_fontfamily_box'];
			if ($opt != '') $result .= 'font-family:'.$opt.'; ';
			$opt = $wppa_opt['wppa_fontsize_box'];
			if ($opt != '') $result .= 'font-size:'.$opt.'px; ';
			$opt = $wppa_opt['wppa_fontcolor_box'];
			if ($opt != '' && $nocolor != 'nocolor') $result .= 'color:'.$opt.'; ';
			break;
		case 'wppa-nav':
			$opt = $wppa_opt['wppa_bgcolor_nav'];
			if ($opt != '') $result .= 'background-color:'.$opt.'; ';
			$opt = $wppa_opt['wppa_bcolor_nav'];
			if ($opt != '') $result .= 'border-color:'.$opt.'; ';
			break;
		case 'wppa-nav-text':
			$opt = $wppa_opt['wppa_fontfamily_nav'];
			if ($opt != '') $result .= 'font-family:'.$opt.'; ';
			$opt = $wppa_opt['wppa_fontsize_nav'];
			if ($opt != '') $result .= 'font-size:'.$opt.'px; ';
			$opt = $wppa_opt['wppa_fontcolor_nav'];
			if ($opt != '') $result .= 'color:'.$opt.'; ';
			break;
		case 'wppa-even':
			$opt = $wppa_opt['wppa_bgcolor_even'];
			if ($opt != '') $result .= 'background-color:'.$opt.'; ';
			$opt = $wppa_opt['wppa_bcolor_even'];
			if ($opt != '') $result .= 'border-color:'.$opt.'; ';
			break;
		case 'wppa-alt':
			$opt = $wppa_opt['wppa_bgcolor_alt'];
			if ($opt != '') $result .= 'background-color:'.$opt.'; ';
			$opt = $wppa_opt['wppa_bcolor_alt'];
			if ($opt != '') $result .= 'border-color:'.$opt.'; ';
			break;
		case 'wppa-img':
			$opt = $wppa_opt['wppa_bgcolor_img'];
			if ($opt != '') $result .= 'background-color:'.$opt.'; ';
			break;
		case 'wppa-title':
			$opt = $wppa_opt['wppa_fontfamily_title'];
			if ($opt != '') $result .= 'font-family:'.$opt.'; ';
			$opt = $wppa_opt['wppa_fontsize_title'];
			if ($opt != '') $result .= 'font-size:'.$opt.'px; ';
			$opt = $wppa_opt['wppa_fontcolor_title'];
			if ($opt != '') $result .= 'color:'.$opt.'; ';
			break;
		case 'wppa-fulldesc':
			$opt = $wppa_opt['wppa_fontfamily_fulldesc'];
			if ($opt != '') $result .= 'font-family:'.$opt.'; ';
			$opt = $wppa_opt['wppa_fontsize_fulldesc'];
			if ($opt != '') $result .= 'font-size:'.$opt.'px; ';
			$opt = $wppa_opt['wppa_fontcolor_fulldesc'];
			if ($opt != '') $result .= 'color:'.$opt.'; ';
			break;
		case 'wppa-fulltitle':
			$opt = $wppa_opt['wppa_fontfamily_fulltitle'];
			if ($opt != '') $result .= 'font-family:'.$opt.'; ';
			$opt = $wppa_opt['wppa_fontsize_fulltitle'];
			if ($opt != '') $result .= 'font-size:'.$opt.'px; ';
			$opt = $wppa_opt['wppa_fontcolor_fulltitle'];
			if ($opt != '') $result .= 'color:'.$opt.'; ';
			break;
//		case 'wppa-black':
//			$opt = $wppa_opt['wppa_black'];
//			if ($opt != '') $result .= 'color:'.$opt.'; ';
//			break;
		case 'wppa-widget':
			$opt = $wppa_opt['wppa_widget_padding_top'];
			if ($opt != '') $result .= 'padding-top:'.$opt.'px; ';
			$opt = $wppa_opt['wppa_widget_padding_left'];
			if ($opt != '') $result .= 'padding-left:'.$opt.'px; ';
			break;
		case 'wppa-arrow':
			$opt = $wppa_opt['wppa_arrow_color'];
			if ($opt != '') $result .= 'color:'.$opt.'; ';
			break;
	}
	return $result;
}

function wppa_dummy_bar($msg = '') {
global $wppa;

	$wppa['out'] .= '<div style="margin:4px 0; '.__wcs('wppa-box').__wcs('wppa-nav').'text-align:center;">'.$msg.'</div>';
}


function wppa_slide_rating($opt = '') {
global $wppa;
global $wppa_opt;

	if ($opt == 'optional' && !$wppa_opt['wppa_rating_on']) return;
	if ($wppa['is_slideonly'] == '1') return;	/* Not when slideonly */
	if (is_feed()) {
		wppa_dummy_bar(__a('- - - Rating enabled - - -', 'wppa_theme'));
		return;
	}
	$fs = $wppa_opt['wppa_fontsize_nav'];	
	$dh = $fs + '6';
	$size = 'font-size:'.$fs.'px;';
	
	$wppa['out'] .= '<div id="wppa-rating-'.$wppa['master_occur'].'" class="wppa-box wppa-nav wppa-nav-text" style="'.__wcs('wppa-box').__wcs('wppa-nav').__wcs('wppa-nav-text').$size.' text-align:center;">';

	$r['1'] = __a('very low', 'wppa_theme');
	$r['2'] = __a('low', 'wppa_theme');
	$r['3'] = __a('average', 'wppa_theme');
	$r['4'] = __a('high', 'wppa_theme');
	$r['5'] = __a('very high', 'wppa_theme');

	if ($fs != '') $fs += 3; else $fs = '15';	// iconsize = fontsize+3, Default to 15
	$style = 'style="height:'.$fs.'px; margin:0 0 -3px 0; padding:0;"';

	$wppa['out'] .= __a('Average&nbsp;rating', 'wppa_theme').'&nbsp;';
	
	$icon = 'star.png';
	$i = '1';
	while ($i < '6') {
		$wppa['out'] .= '<img id="wppa-avg-'.$wppa['master_occur'].'-'.$i.'" class="wppa-avg-'.$wppa['master_occur'].' no-shadow" '.$style.' src="'.wppa_get_imgdir().$icon.'" alt="'.$i.'" title="'.__a('Average&nbsp;rating', 'wppa_theme').': '.$r[$i].'" />';
		$i++;
	}
	
	$wppa['out'] .= '&nbsp;&nbsp;';

	if (!$wppa_opt['wppa_rating_login'] || is_user_logged_in()) {
		$i = '1';
		while ($i < '6') {
			$wppa['out'] .= '<img id="wppa-rate-'.$wppa['master_occur'].'-'.$i.'" class="wppa-rate-'.$wppa['master_occur'].' no-shadow" '.$style.' src="'.wppa_get_imgdir().$icon.'" alt="'.$i.'" title="'.__a('My&nbsp;rating', 'wppa_theme').': '.$r[$i].'" onmouseover="wppa_follow_me('.$wppa['master_occur'].', '.$i.')" onmouseout="wppa_leave_me('.$wppa['master_occur'].', '.$i.')" onclick="wppa_rate_it('.$wppa['master_occur'].', '.$i.')" />';
			$i++;
		}
		$wppa['out'] .= '&nbsp;'.__a('My&nbsp;rating', 'wppa_theme');
	}
	else {
		$wppa['out'] .= __a('You must login to vote', 'wppa_theme');
	}
	$wppa['out'] .= '</div>';
}

function wppa_rating_count_by_id($id = '') {
global $wppa;

	$wppa['out'] .= wppa_get_rating_count_by_id($id);
}


function wppa_rating_by_id($id = '', $opt = '') {
global $wppa;

	$wppa['out'] .= wppa_get_rating_by_id($id, $opt);
}

function wppa_get_cover_width($type) {
global $wppa_opt;

	$conwidth = wppa_get_container_width();
	$cols = wppa_get_cover_cols($type);
	
	switch ($cols) {
		case '1':
			$result = $conwidth;
			break;
		case '2':
			$result = floor(($conwidth - 8) / 2);
			break;
		case '3':
			$result = floor(($conwidth - 16) / 3);
			break;
		
	}
	$result -= (2 * (7 + $wppa_opt['wppa_bwidth']));	// 2 * (padding + border)
	return $result;
}

function wppa_get_text_frame_style($photo_left, $type) {
global $wppa_opt;

	$width = wppa_get_cover_width($type); // - wppa_get_textframe_delta($type);
	$width -= $wppa_opt['wppa_smallsize'];
	$width -= 13;	// margin
	
	if ($photo_left) {
		$result = 'style="width:'.$width.'px; float:right;"';
	}
	else {
		$result = 'style="width:'.$width.'px; float:left;"';// position:absolute;"';
	}
	return $result;
}

function wppa_get_textframe_delta() {
global $wppa_opt;

	$delta = $wppa_opt['wppa_smallsize'];
	$delta += (2 * (7 + $wppa_opt['wppa_bwidth'] + 4) + 5);	// 2 * (padding + border + photopadding) + margin
	return $delta;
}

function wppa_step_covercount($type) {
global $cover_count;

	$cols = wppa_get_cover_cols($type);
	switch ($cols) {
		case 1:
		break;
		case 2:
			$cover_count++;
			if ($cover_count == '2') $cover_count = '0';
		break;
		case 3:
			$cover_count++;
			if ($cover_count == '3') $cover_count = '0';
			break;
	}
}

function wppa_get_cover_cols($type) {
global $wppa;
global $wppa_opt;

	$conwidth = wppa_get_container_width();
	$cols = '1';
	if ($conwidth >= $wppa_opt['wppa_2col_treshold']) $cols = '2';
	if ($conwidth >= $wppa_opt['wppa_3col_treshold']) $cols = '3';
	
	if ($wppa['auto_colwidth']) $cols = '1';
	if (($type == 'cover') && ($wppa['album_count'] < '2')) $cols = '1';
	if (($type == 'thumb') && ($wppa['thumb_count'] < '2')) $cols = '1';
	return $cols;
}

function wppa_get_box_width() {
global $wppa_opt;

	$result = wppa_get_container_width();
	$result -= 14;	// 2 * padding
	$result -= 2 * $wppa_opt['wppa_bwidth'];
	return $result;
}

function wppa_get_box_delta() {
	return wppa_get_container_width() - wppa_get_box_width();
}

function __a($txt, $dom) {
	return __($txt, $dom);
}

// get permalink plus ? or &
function wppa_get_permalink($key = '') {
global $wppa;
	
	switch ($key) {
		case '0':
		case '':	// normal permalink
//			if ($wppa['permalink'] == '') {	// not in cache
				$pl = get_permalink();
				if (strpos($pl, '?')) $pl .= '&amp;';
				else $pl .= '?';
//				$wppa['permalink'] = $pl;	// cache it
//			}
//			else {
//				$pl = $wppa['permalink'];
//			}
			break;
		case 'js':	// normal permalink for js use
			$pl = get_permalink();
			if (strpos($pl, '?')) $pl .= '&';
			else $pl .= '?';
			break;
		default:	// pagelink
			$pl = get_page_link($key);
			if (strpos($pl, '?')) $pl .= '&amp;';
			else $pl .= '?';
			break;
	}
	return $pl;
}

function wppa_force_balance_pee($xtext) {

	$text = $xtext;	// Make a local copy
	$done = false;
	$temp = strtolower($text);
	
	// see if this chunk ends in <p> in which case we remove that in stead of appending a </p>
	$len = strlen($temp);
	if ($len > 3) {
		if (substr($temp, $len - 3) == '<p>') {
			$text = substr($text, 0, $len - 3);
			$temp = strtolower($text);
		}
	}
	
	$opens = substr_count($temp, '<p');
	$close = substr_count($temp, '</p');
	// append a close
	if ($opens > $close) {	
		$text .= '</p>';	
	}
	// prepend an open
	if ($close > $opens) {	
		$text = '<p>'.$text;
	}
	return $text;
}

// This is a nice simple function
function wppa_output($txt) {
global $wppa;

	$wppa['out'] .= $txt;
	return;
}
	
function wppa_mphoto() {
global $wppa;
global $wppa_opt;

	$width = wppa_get_container_width();
	$height = floor($width / wppa_get_ratio($wppa['single_photo']));
	
	$wppa['out'] .= '[caption id="wppa_'.$wppa['single_photo'].'" ';
	if ($wppa['align'] != '') $wppa['out'] .= 'align="align'.$wppa['align'].'" ';
	$wppa['out'] .= 'width="'.$width.'" ';
	$wppa['out'] .= 'caption="'.strip_tags(stripslashes(wppa_get_photo_desc($wppa['single_photo']))).'"]';
	$link = wppa_get_imglnk_a('mphoto', $wppa['single_photo']);
	if ($link) {
		$wppa['out'] .= '<a href="'.$link['url'].'" title="'.$link['title'].'" class="thumb-img" id="a-'.$wppa['single_photo'].'-'.$wppa['master_occur'].'">';
	}
	$wppa['out'] .= '<img src="'.wppa_get_image_url_by_id($wppa['single_photo']).'" alt="" class="size-medium" title="'.esc_attr(stripslashes(wppa_get_photo_name($wppa['single_photo']))).'" width="'.$width.'" height="'.$height.'" />';
	if ($link) {
		$wppa['out'] .= '</a>';
	}
	$wppa['out'] .= '[/caption]';
}

// returns aspect ratio (w/h), or 1 on error
function wppa_get_ratio($id = '') {
global $wpdb;

	if (!is_numeric($id)) return '1';	// Not 0 to prevent divide by zero
	
	$photo = $wpdb->get_row("SELECT * FROM " . WPPA_PHOTOS . " WHERE id={$id} LIMIT 1", 'ARRAY_A');
	if (!$photo) return '1';
	
	$file = ABSPATH.'/wp-content/uploads/wppa/'.$id.'.'.$photo['ext'];
	if (is_file($file)) $image_attr = getimagesize($file);
	else return '1';
	
	if ($image_attr[1] != 0) return $image_attr[0]/$image_attr[1];	// width/height
	return '1';
}

function wppa_get_album_id_by_photo_id($photo) {
global $wpdb;

	if (is_numeric($photo)) $album = $wpdb->get_var("SELECT album FROM ".WPPA_PHOTOS." WHERE id={$photo} LIMIT 1");
	return $album;
}

function wppa_get_searchstring() {
	$src = '';
	if (isset($_POST['wppa-searchstring'])) {
		$src = $_POST['wppa-searchstring'];
	}
	elseif (isset($_GET['wppa_src'])) {
		$src = $_GET['wppa_src'];
	}
	return $src;
}

function wppa_get_imglnk_a($wich, $photo, $lnk = '', $tit = '') {
global $wppa;
global $wppa_opt;
global $thumb;
global $wpdb;

	switch ($wich) {
		case 'mphoto':
			$type = $wppa_opt['wppa_mphoto_linktype'];
			$page = $wppa_opt['wppa_mphoto_linkpage'];
			if ($page == '0') $page = '-1';
			break;
		case 'thumb':
			$type = $wppa_opt['wppa_thumb_linktype'];
			$page = $wppa_opt['wppa_thumb_linkpage'];
			break;
		case 'topten':
			$type = $wppa_opt['wppa_topten_widget_linktype'];
			$page = $wppa_opt['wppa_topten_widget_linkpage'];
			if ($page == '0') $page = '-1';
			break;
		case 'sswidget':
			$type = $wppa_opt['wppa_slideonly_widget_linktype'];
			$page = $wppa_opt['wppa_slideonly_widget_linkpage'];
			if ($page == '0') $page = '-1';
			break;
		case 'potdwidget':
			$type = $wppa_opt['wppa_widget_linktype'];
			$page = $wppa_opt['wppa_widget_linkpage'];
			if ($page == '0') $page = '-1';
			break;
		case 'coverimg':
			$type = $wppa_opt['wppa_coverimg_linktype'];
			$page = $wppa_opt['wppa_coverimg_linkpage'];
			if ($page == '0') $page = '-1';
			break;
		default:
			return false;
			break;
	}
	$album = wppa_get_album_id_by_photo_id($photo);
	$album_name = wppa_get_album_name($album);
	$photo_name = false;
	if (is_array($thumb)) {
		if ($thumb['id'] == $photo) {
			$photo_name = wppa_qtrans(stripslashes($thumb['name']));
		}
	}
	if (!$photo_name) $photo_name = wppa_get_photo_name($photo);
	$photo_name = esc_attr($photo_name);
	
	switch ($type) {
		case 'none':		// No link at all
			return false;
			break;
		case 'file':		// The plain file
			$result['url'] = wppa_get_photo_url($photo);
			$result['title'] = $photo_name;
			return $result;
			break;
		case 'widget':		// Defined at widget activation
			$result['url'] = $wppa['in_widget_linkurl'];
			$result['title'] = esc_attr($wppa['in_widget_linktitle']);
			return $result;
			break;
		case 'album':		// The albums thumbnails
			switch ($page) {
				case '-1':
					return false;
					break;
				case '0':
					$result['url'] = wppa_get_permalink().'album='.$album.'&amp;cover=0';
					$result['title'] = $album_name;//'a-0';
					break;
				default:
					$result['url'] = wppa_get_permalink($page).'album='.$album.'&amp;cover=0';
					$result['title'] = $album_name;//'a++';
					break;
			}
			break;
		case 'photo':
			switch ($page) {
				case '-1':
					return false;
					break;
				case '0':
					$result['url'] = wppa_get_permalink().'album='.$album.'&amp;photo='.$photo;
					$result['title'] = $photo_name;//'p-0';
					break;
				default:
					$result['url'] = wppa_get_permalink($page).'album='.$album.'&amp;photo='.$photo;
					$result['title'] = $photo_name;//'p++';
					break;
			}
			break;
		case 'single':
			switch ($page) {
				case '-1':
					return false;
					break;
				case '0':
					$result['url'] = wppa_get_permalink().'&amp;photo='.$photo;
					$result['title'] = $photo_name;//'s-0';
					break;
				default:
					$result['url'] = wppa_get_permalink($page).'&amp;photo='.$photo;
					$result['title'] = $photo_name;//'s++';
					break;
			}
			break;
		case 'indiv':
			$data = $wpdb->get_row('SELECT * FROM '.WPPA_PHOTOS.' WHERE id='.$photo.' LIMIT 1', 'ARRAY_A');
			if ($data) {
				if ($data['linkurl'] != '') {
					$result['url'] = esc_attr($data['linkurl']);
					$result['title'] = esc_attr(wppa_qtrans(stripslashes($data['linktitle'])));
				}
				else $result = false;
			}
			else $result = false;
			return $result;
			break;
		case 'same':
			$result['url'] = $lnk;
			$result['title'] = $tit;
			return $result;
			break;
		default:
			return false;
			break;
	}
	
	if (isset($_POST['wppa-searchstring'])) {
		$result['url'] .= '&amp;wppa_src='.$_POST['wppa-searchstring'];
	}
	if ($wich == 'topten') {
		$result['url'] .= '&amp;topten='.$wppa_opt['wppa_topten_count'];
	}
	if ($page != '0') {	// on a different page
		$occur = '1';
		$w = '';
	}
	else {				// on the same page, post or widget
		$occur = $wppa['in_widget'] ? $wppa['widget_occur'] : $wppa['occur'];
		$w = $wppa['in_widget'] ? 'w' : '';
	}
	$result['url'] .= '&amp;'.$w.'occur='.$occur;
	
	return $result;
}