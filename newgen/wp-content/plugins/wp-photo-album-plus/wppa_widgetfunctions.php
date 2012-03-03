<?php
/* wppa_widgetfunctions.php
/* Package: wp-photo-album-plus
/*
/* Version 3.0.0
/*
*/

function wppa_get_widgetphotos($alb, $option = '') {
	global $wpdb;
	
	$photos = false;
	
	// Is it a single album?
	if (is_numeric($alb)) {
		$query = 'SELECT * FROM ' . WPPA_PHOTOS . ' WHERE album=' . $alb . ' ' . $option;
		$photos = $wpdb->get_results($query, 'ARRAY_A');
	}
	// Is it an enumeration of album ids?
	elseif (strchr($alb, ',')) {
		$albs = explode(',', $alb);
		$query = 'SELECT * FROM ' . WPPA_PHOTOS;
		$first = true;
		foreach ($albs as $a) if (is_numeric($a)) {
			if ($first) $query .= ' WHERE ';
			else $query .= ' OR ';
			$first = false;
			$query .= ' album=' . $a;
		}
		$query .= ' ' . $option;
		$photos = $wpdb->get_results($query, 'ARRAY_A');
	}
	// Is it ALL?
	elseif ($alb == 'all') {
		$query = 'SELECT * FROM ' . WPPA_PHOTOS . ' ' . $option;
		$photos = $wpdb->get_results($query, 'ARRAY_A');
	}
	// Is it SEP?
	elseif ($alb == 'sep') {
		$albs = $wpdb->get_results('SELECT id, a_parent FROM ' . WPPA_ALBUMS, 'ARRAY_A');
		$query = 'SELECT * FROM ' . WPPA_PHOTOS;
		$first = true;
		foreach ($albs as $a) {
			if ($a['a_parent'] == '-1') {
				if ($first) $query .= ' WHERE ';
				else $query .= ' OR ';
				$first = false;
				$query .= ' album=' . $a['id'];
			}
		}
		$query .= ' ' . $option;
		$photos = $wpdb->get_results($query, 'ARRAY_A');
	}	
	// Is it ALL-SEP?
	elseif ($alb == 'all-sep') {
		$albs = $wpdb->get_results('SELECT id, a_parent FROM ' . WPPA_ALBUMS, 'ARRAY_A');
		$query = 'SELECT * FROM ' . WPPA_PHOTOS;
		$first = true;
		foreach ($albs as $a) {
			if ($a['a_parent'] != '-1') {
				if ($first) $query .= ' WHERE ';
				else $query .= ' OR ';
				$first = false;
				$query .= ' album=' . $a['id'];
			}
		}
		$query .= ' ' . $option;
		$photos = $wpdb->get_results($query, 'ARRAY_A');
	}
	
	return $photos;
}

// get select form element listing albums 
// Special version for widget
function wppa_walbum_select($sel = '') {
	global $wpdb;
	$albums = $wpdb->get_results("SELECT * FROM " . WPPA_ALBUMS . " ORDER BY name", 'ARRAY_A');
	
	if (is_numeric($sel)) $type = 1;		// Single number
	elseif (strchr($sel, ',')) {
		$type = 2;							// Array
		$albs =  explode(',', $sel);
	}
	elseif ($sel == 'all') $type = 3;		// All
	elseif ($sel == 'sep') $type = 4;		// Separate only
	elseif ($sel == 'all-sep') $type = 5;	// All minus separate
	else $type = 0;							// Nothing yet
    
    $result = '<option value="" selected="selected">'.__('- select album(s) -', 'wppa').'</option>';
    
	foreach ($albums as $album) {
		switch ($type) {
			case 1:
				$dis = ($album['id'] == $sel);
			break;
			case 2:
				$dis = in_array($album['id'], $albs);
			break;
			case 3:
				$dis = true;
			break;
			case 4:
				$dis = ($album['a_parent'] == '-1');
			break;
			case 5:
				$dis = ($album['a_parent'] != '-1');
			break;
			default:
				$dis = false;
		}
		if ($dis) $dis = 'disabled="disabled"';
		else $dis = '';
		$result .= '<option '.$dis.' value="' . $album['id'] . '">(' . $album['id'] . ')';
			if ($album['id'] < '1000') $result .= '&nbsp;';
			if ($album['id'] < '100') $result .= '&nbsp;';
			if ($album['id'] < '10') $result .= '&nbsp;';
			$result .= stripslashes($album['name']) . '</option>';
	}
    $result .= '<option value="all" >'.__('- all albums -', 'wppa').'</option>';
	$result .= '<option value="sep" >'.__('- all -separate- albums -', 'wppa').'</option>';
	$result .= '<option value="all-sep" >'.__('- all albums except -separate-', 'wppa').'</option>';
	return $result;
}

function wppa_walbum_sanitize($walbum) {
	$result = strtolower($walbum);
	if (strstr($result, 'all-sep')) $result = 'all-sep';
	elseif (strstr($result, 'all')) $result = 'all';
	elseif (strstr($result, 'sep')) $result = 'sep';
	else {
		while (substr_count($result, ',,')) {
			$pos = strpos($result, ',,');
			$res = substr_replace($result, ',', $pos, 2);
			$result = $res;
		}
		// remove leading comma
		if (substr($result, 0, 1) == ',') $result = substr($result, 1);
		// remove trailing comma
		if (substr($result, strlen($result) - 1, 1) == ',') $result = substr($result, 0, strlen($result) - 1);
	}
//echo('In:'.$walbum.'Out:'.$result);	
	return $result;
}
