<?php
/*
Plugin Name: Keyword Statistics
Plugin URI: http://www.keyword-statistics.net/wordpress-plugin.html
Description: The Keyword-Statistics-Plugin checks the content of a post or a page for <i>keyword-density</i> (single words and optionally 2- and 3-word phrases; for each the 1-10 most commonly used can be displayed). It can update its informations <i>automatically while the author is writing his content</i> in a variable interval (every 1-10 seconds) or manually by clicking on a button. The script comes with english and german <i>stopwords</i>, which optionally can be filtered out before calculating the keyword-densities. Moreover the commonest keywords are extracted in a list as a meta-keyword suggestion. Based on this list a description can be created and automatically set.
Version: 1.7.7
Author: Alexander Müller
Author URI: http://www.keyword-statistics.net
*/
/*
Copyright (C) 2009-2010 Alexander Müller, (webmaster AT keyword-statistics DOT net)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

$ks_plugin_version = '1.7.7';

// Language
$ks_plugin_dir = basename (dirname (__FILE__));
load_plugin_textdomain ('keyword-statistics', 'wp-content/plugins/' . $ks_plugin_dir, $ks_plugin_dir );

// get plugins configuration
function keyword_statistics_get_plugin_configuration () {
	global $wp_version;
	if (substr ($wp_version, 0, 3) >= '2.7')
		// WP 2.7+
		$options = get_option ('keyword_statistics_configuration');
	else {
		$options = array ();
		$options['version'] = get_option ('ks_version');
		$options['testmode'] = get_option ('ks_testmode');
		$options['default_language'] = get_option ('ks_default_language');
		$options['set_language_meta'] = get_option ('ks_set_language_meta');
		$options['filter_stopwords'] = get_option ('ks_filter_stopwords');
		$options['max_list_items'] = get_option ('ks_max_list_items');
		$options['automatic_update'] = get_option ('ks_automatic_update');
		$options['update_interval'] = get_option ('ks_update_interval');
		$options['2word_phrases'] = get_option ('ks_2word_phrases');
		$options['3word_phrases'] = get_option ('ks_3word_phrases');
		$options['meta_keywords_count'] = get_option ('ks_meta_keywords_count');
		$options['max_keywords_count'] = get_option ('ks_max_keywords_count');
		$options['max_keywords_length'] = get_option ('ks_keywords_length');
		$options['index_aggregated'] = get_option ('ks_index_aggregated');
		$options['follow_aggregated'] = get_option ('ks_follow_aggregated');
		$options['noodp'] = get_option ('ks_noodp');
		$options['noydir'] = get_option ('ks_noydir');
		$options['noarchive'] = get_option ('ks_noarchive');
		$options['dont_serve_metadata'] = get_option ('ks_dont_serve_metadata');
		$options['serve_title'] = get_option ('ks_serve_title');
		$options['serve_meta_robots'] = get_option ('ks_serve_meta_robots');
		$options['serve_meta_keywords'] = get_option ('ks_serve_meta_keywords');
		$options['serve_meta_description'] = get_option ('ks_serve_meta_description');
		$options['serve_meta_canonical'] = get_option ('ks_serve_meta_canonical');
		$options['words_from_posts_in_aggregated_description'] = get_option ('ks_words_from_posts_in_aggregated_description');
		$options['max_title_length'] = get_option ('ks_max_title_length');
		$options['min_description_length'] = get_option ('ks_min_description_length');
		$options['max_description_length'] = get_option ('ks_max_description_length');
		$options['automatic_meta_data_update'] = get_option ('ks_automatic_meta_data_update');
		$options['min_words'] = get_option ('ks_min_words');
		$options['hide_fields_authors_cant_change'] = get_option ('ks_hide_fields_authors_cant_change');
		$options['hide_fields_authors_cant_change_from_admin'] = get_option ('ks_hide_fields_authors_cant_change_from_admin');
		$options['authors_can_change_content_language'] = get_option ('ks_authors_can_change_content_language');
		$options['authors_can_disable_stopword_filter'] = get_option ('ks_authors_can_disable_stopword_filter');
		$options['authors_can_set_individual_title'] = get_option ('ks_authors_can_set_individual_title');
		$options['authors_can_edit_keywords'] = get_option ('ks_authors_can_edit_keywords');
		$options['authors_can_edit_description'] = get_option ('ks_authors_can_edit_description');
		$options['authors_can_control_robots'] = get_option ('ks_authors_can_control_robots');
		$options['home_first'] = get_option ('ks_home_first');
		$options['category_first'] = get_option ('ks_category_first');
		$options['tag_first'] = get_option ('ks_tag_first');
		$options['archive_first'] = get_option ('ks_archive_first');
		$options['author_first'] = get_option ('ks_author_first');
		$options['search_first'] = get_option ('ks_search_first');
		$options['home_index'] = get_option ('ks_home_index');
		$options['category_index'] = get_option ('ks_category_index');
		$options['tag_index'] = get_option ('ks_tag_index');
		$options['archive_index'] = get_option ('ks_archive_index');
		$options['author_index'] = get_option ('ks_author_index');
		$options['search_index'] = get_option ('ks_search_index');
		$options['home_follow'] = get_option ('ks_home_follow');
		$options['category_follow'] = get_option ('ks_category_follow');
		$options['tag_follow'] = get_option ('ks_tag_follow');
		$options['archive_follow'] = get_option ('ks_archive_follow');
		$options['author_follow'] = get_option ('ks_author_follow');
		$options['search_follow'] = get_option ('ks_search_follow');
		$options['feeds_noindex'] = get_option ('ks_feeds_noindex');
	}
	return $options;
}

// update plugins configuration
function keyword_statistics_set_plugin_configuration ($options) {
	global $wp_version;
	if (substr ($wp_version, 0, 3) >= '2.7')
		update_option ('keyword_statistics_configuration', $options);
	else
		foreach ($options as $key => $value)
			update_option ('ks_' . $key, $value);
}

// get the canonical url of a page, post or various content aggregations including pagination
function ks_canonical ($urlstruct) {
	$p = '';
	if (is_array ($urlstruct)) {
		$home = get_option ('siteurl') != get_option ('home') ? get_option ('home') : get_option ('siteurl');
		$has_permalink_structure = get_option ('permalink_structure') ? true : false;
		$permalink = get_option ('permalink_structure');
		switch ($urlstruct['type']) {
			case 'home':		$p = $home . '/';
						// if postlist is set to a page url in configuration, we have to use that url for canonical
						if (get_option ('show_on_front') == 'page' && get_option ('page_for_posts') != '')
							$p = get_permalink (get_option ('page_for_posts'));
						break;
			case 'tag':
			case 'category':	$p = $home;
						if ($has_permalink_structure)
							$p .= ($urlstruct['type'] == 'category' ? (get_option ('category_base') ? '/' . get_option ('category_base') : substr ($permalink, 0, strpos ($permalink, '%')) . 'category') :
												  (get_option ('tag_base') ? '/' . get_option ('tag_base') : substr ($permalink, 0, strpos ($permalink, '%')) . 'tag'));
						if (is_int ($urlstruct['id']) || is_string ($urlstruct['name']))
							$p .=  '/' . ($urlstruct['type'] == 'category' ? ($has_permalink_structure ? $urlstruct['name'] . (strrpos ($permalink, '/') == strlen ($permalink) - 1 ? '/' : '') : '?cat=' . $urlstruct['id']) :
													 ($has_permalink_structure ? $urlstruct['name'] . (strrpos ($permalink, '/') == strlen ($permalink) - 1 ? '/' : '') : '?tag=' . $urlstruct['name']));
						else
							$p = '';
						break; 
			case 'author':		$p = $home;
						if ($has_permalink_structure)
							$p .= substr ($permalink, 0, strpos ($permalink, '%')) . 'author';
						if (is_int ($urlstruct['id']) || is_string ($urlstruct['name']))
							$p .=  '/' . ($has_permalink_structure ? $urlstruct['name'] . (strrpos ($permalink, '/') == strlen ($permalink) - 1 ? '/' : '') : '?author=' . $urlstruct['id']);
						else
							$p = '';
						break;
			case 'page':
			case 'post':		$p = get_permalink ($urlstruct['id']) . (get_option ('show_on_front') == 'page' && get_option ('page_on_front') == $urlstruct['id'] ? '/' : '');
						break;
			case 'archive':		if ($urlstruct['year'] && $urlstruct['month'] && $urlstruct['day'])
							$p = get_day_link ($urlstruct['year'], $urlstruct['month'], $urlstruct['day']);
						else if ($urlstruct['year'] && $urlstruct['month'])
							$p = get_month_link ($urlstruct['year'], $urlstruct['month']);
						else if ($urlstruct['year'])
							$p = get_year_link ($urlstruct['year']);
						break;
		}
		if (isset ($urlstruct['page']) && is_int ($urlstruct['page']))
			$p .= !get_option ('permalink_structure') ? (strpos ($p, '?') != false ? '&paged=' : '?paged=') . $urlstruct['page'] : ((strrpos ($permalink, '/') == strlen ($permalink) - 1 && $urlstruct['type'] != 'home') || $urlstruct['type'] == 'home' ? '' : '/') . 'page/' . $urlstruct['page'] . (strrpos ($permalink, '/') == strlen ($permalink) - 1 ? '/' : '');
	}
	return $p;
}

// replace variables in a string
function ks_replace_variables ($s, $v = NULL) {
	global $wp_query;
	if (preg_match_all ('/%([^%]+)%/', $s, $matches)) {
		$matches = array_unique ($matches[1]);
		$replacement = '';
		foreach ($matches as $key)
			switch ($key) {
				case 'blogname':
					$s = preg_replace ('/%blogname%/', get_option ('blogname'), $s);
					break;
				case 'siteurl':
					$replacement = preg_match ('/http[s]?:\/\/(.*)/', get_option ('siteurl') != get_option ('home') ? get_option ('home') : get_option ('siteurl'), $res) ? $res[1] : '';
					$s = preg_replace ('/%siteurl%/', $replacement, $s);
					break;
				case 'category_name':
					if (is_category ()) {
						$category_info = get_category ($wp_query->query_vars['cat']);
						$replacement = $category_info->slug;
					}
					else if ($v['category_name'])
						$replacement = $v['category_name'];
					$s = preg_replace ('/%category_name%/', $replacement, $s);
					break;
				case 'tag_name':
					if ($wp_query->query['tag']) {
						$replacement = $wp_query->query['tag'];
					}
					else if ($v['tag_name'])
						$replacement = $v['tag_name'];
					$s = preg_replace ('/%tag_name%/', $replacement, $s);
					break;
				case 'author_name':
					if ($wp_query->query['author']) {
						$user_data = get_userdata ($wp_query->query['author']);
						$replacement = $user_data->user_login;
					}
					else if ($v['author_name'])
						$replacement = $v['author_name'];
					$s = preg_replace ('/%author_name%/', $replacement, $s);
					break;
				case 'archive_date':
					if ($wp_query->query['m'])
						$replacement = $wp_query->query['m'];
					else if ($v['m'])
						$replacement = $v['m'];
					if (strlen ($replacement) == 4)
						$replacement = __('Archive of', 'keyword-statistics') . ' ' . $replacement;
					else if (strlen ($replacement) == 6)
						$replacement = __('Archive of', 'keyword-statistics') . ' ' . date_i18n ('F/Y', mktime (0, 0, 0, intval (substr ($replacement, 4)), 1, intval (substr ($replacement, 0, 4))));
					else if (strlen ($replacement) == 8)
						$replacement = __('Archive of', 'keyword-statistics') . ' ' . date_i18n (isset ($v['date_format']) ? $v['date_format'] : get_option ('date_format'), mktime (0, 0, 0, intval (substr ($replacement, 4, 2)), intval (substr ($replacement, 6)), intval (substr ($replacement, 0, 4))));

					$s = preg_replace ('/%archive_date%/', $replacement, $s);
					break;
				case 'search_query':
					if ($wp_query->query['s'])
						$replacement = $wp_query->query['s'];
					else if ($v['s'])
						$replacement = $v['s'];
					$s = preg_replace ('/%search_query%/', $replacement, $s);
					break;
				case 'pagination':
					if (isset ($wp_query->query_vars['paged']) && $wp_query->query_vars['paged'] > 0)
						$replacement = __('&ndash; page', 'keyword-statistics') . ' ' . $wp_query->query_vars['paged'];
					else if (intval ($v['paged']) > 1)
						$replacement = __('&ndash; page', 'keyword-statistics') . ' ' . $v['paged'];
					$s = preg_replace ('/%pagination%/', $replacement, $s);
					break;
			}
	}
	// drop all other variables which could not be replaced in a particular
	// section or the plugin doesn't know
	$s = preg_replace ('/%[^%]+%/', '', $s);
	return $s;
}

// putting meta keywords, language, robots, description and canonical url into header
function keyword_statistics_add_meta_tags () {
	global $post, $posts, $wp_query, $ks_plugin_version;

	$options = keyword_statistics_get_plugin_configuration ();
	if ($options['dont_serve_metadata'] && !$options['testmode']) {
		// nothing to do here if no meta information has to be inserted
		echo "\n<!-- metadata insertion deactivated - keyword-statistics-plugin $ks_plugin_version http://www.keyword-statistics.net -->\n";
		return;
	}
	echo "\n<!-- metadata inserted by keyword-statistics-plugin $ks_plugin_version http://www.keyword-statistics.net " . ($options['testmode'] ? '' : '-->') . "\n";
	if (is_404 ())
		$meta['robots'] = 'noindex,nofollow';
	else if (is_page () || is_single ()) {
		// get meta data for post or page
		$meta = get_post_meta ($post->ID, 'ks_metadata', true);
		//$meta['canonical'] = get_permalink ($post->ID) . (get_option ('show_on_front') == 'page' && get_option ('page_on_front') == $post->ID ? '/' : '');
		$meta['canonical'] = ks_canonical (array ('type' => is_page () ? 'page' : 'post', 'id' => $post->ID));
	}
	else if ((get_option ('show_on_front') == 'posts' || (get_option ('show_on_front') == 'page' && get_option ('page_on_front') == 0) || get_option ('page_for_posts') != '' || is_category () || /*is_tag ()*/ $wp_query->query['tag'] || $wp_query->query['year'] || $wp_query->query['author_name']) && $wp_query->post_count > 0) {
		$isAggregation = TRUE;
		if (is_category ()) {
			if (!$wp_query->query_vars['paged'] || ($wp_query->query_vars['paged'] && $options['category_first'] == 0)) {
				if ($options['category_title'])
					$meta['title'] = $options['category_title'];
				if ($options['category_keywords'])
					$meta['keywords'] = $options['category_keywords'];
				if ($options['category_description'])
					$meta['description'] = $options['category_description'];
				$meta['robots'] = $options['category_index'] != 'default' ? $options['category_index'] : ($options['index_aggregated'] ? 'index' : 'noindex');
				$meta['robots'] .= ',' . ($options['category_follow'] != 'default' ? $options['category_follow'] : ($options['follow_aggregated'] ? 'follow' : 'nofollow'));
			}
		}
		else if ($wp_query->query['tag']) {
			if (!$wp_query->query_vars['paged'] || ($wp_query->query_vars['paged'] && $options['tag_first'] == 0)) {
				if ($options['tag_title'])
					$meta['title'] = $options['tag_title'];
				if ($options['tag_keywords'])
					$meta['keywords'] = $options['tag_keywords'];
				if ($options['tag_description'])
					$meta['description'] = $options['tag_description'];
				$meta['robots'] = $options['tag_index'] != 'default' ? $options['tag_index'] : ($options['index_aggregated'] ? 'index' : 'noindex');
				$meta['robots'] .= ',' . ($options['tag_follow'] != 'default' ? $options['tag_follow'] : ($options['follow_aggregated'] ? 'follow' : 'nofollow'));
			}
		}
		else if ($wp_query->query['author_name'] || $wp_query->query['author']) {
			if (!$wp_query->query_vars['paged'] || ($wp_query->query_vars['paged'] && $options['author_first'] == 0)) {
				if ($options['author_title'])
					$meta['title'] = $options['author_title'];
				if ($options['author_keywords'])
					$meta['keywords'] = $options['author_keywords'];
				if ($options['author_description'])
					$meta['description'] = $options['author_description'];
				$meta['robots'] = $options['author_index'] != 'default' ? $options['author_index'] : ($options['index_aggregated'] ? 'index' : 'noindex');
				$meta['robots'] .= ',' . ($options['author_follow'] != 'default' ? $options['author_follow'] : ($options['follow_aggregated'] ? 'follow' : 'nofollow'));
			}
		}
		else if ($wp_query->query['year']) {
			if (!$wp_query->query_vars['paged'] || ($wp_query->query_vars['paged'] && $options['archive_first'] == 0)) {
				if ($options['archive_title'])
					$meta['title'] = $options['archive_title'];
				if ($options['archive_keywords'])
					$meta['keywords'] = $options['archive_keywords'];
				if ($options['archive_description'])
					$meta['description'] = $options['archive_description'];
				$meta['robots'] = $options['archive_index'] != 'default' ? $options['archive_index'] : ($options['index_aggregated'] ? 'index' : 'noindex');
				$meta['robots'] .= ',' . ($options['archive_follow'] != 'default' ? $options['archive_follow'] : ($options['follow_aggregated'] ? 'follow' : 'nofollow'));
			}
		}
		else if ($wp_query->query['s']) {
			if (!$wp_query->query_vars['paged'] || ($wp_query->query_vars['paged'] && $options['search_first'] == 0)) {
				if ($options['search_title'])
					$meta['title'] = $options['search_title'];
				if ($options['search_keywords'])
					$meta['keywords'] = $options['search_keywords'];
				if ($options['search_description'])
					$meta['description'] = $options['search_description'];
				$meta['robots'] = $options['search_index'] != 'default' ? $options['search_index'] : ($options['index_aggregated'] ? 'index' : 'noindex');
				$meta['robots'] .= ',' . ($options['search_follow'] != 'default' ? $options['search_follow'] : ($options['follow_aggregated'] ? 'follow' : 'nofollow'));
			}
		}
		else if (!$wp_query->query_vars['paged'] || ($wp_query->query_vars['paged'] && $options['home_first'] == 0)) {
			if ($options['home_title'])
				$meta['title'] = $options['home_title'];
			if ($options['home_keywords'])
				$meta['keywords'] = $options['home_keywords'];
			if ($options['home_description'])
				$meta['description'] = $options['home_description'];
			$meta['robots'] = $options['home_index'] != 'default' ? $options['home_index'] : ($options['index_aggregated'] ? 'index' : 'noindex');
			$meta['robots'] .= ',' . ($options['home_follow'] != 'default' ? $options['home_follow'] : ($options['follow_aggregated'] ? 'follow' : 'nofollow'));
		}
		// replace variables used in meta informations
		if ($meta['title']) {
			// if we have already integrated pagination in aggregations meta strings, we don't need to add it automatically
			$hasPagination = strlen (strstr ($meta['title'], '%pagination%')) > 0 ? TRUE : FALSE;
			$meta['title'] = ks_replace_variables ($meta['title']);
		}
		if ($meta['keywords'])
			//$meta['keywords'] = ks_replace_variables ($meta['keywords']);
			$meta['keywords'] = preg_replace ('/,+/', ',', preg_replace ('/ *, */', ',', preg_replace ('/ +/', ' ', preg_replace ('/,+/', ',', ks_replace_variables ($meta['keywords'])))));
		if ($meta['description'])
			$meta['description'] = ks_replace_variables ($meta['description']);
		// generate meta data for several content aggregations (category-, tag-, author- and archive-views)
		// get language, keywords and descriptions of aggregated posts
		$keys = array ();
		$descriptions = array ();
		// for counting the posts and pages with the same language
		$same_language = 1;
		for ($i = 0; $i < count ($posts); $i++) {
			$k = get_post_meta ($posts[$i]->ID, 'ks_metadata', true);
			if ($i == 0)
				// let's take the language of the first post
				$meta['lang'] = trim ($k['lang']);
			else
				// is the post or page written in the same language as the first one?
				$same_language += trim ($k['lang']) == $meta['lang'] ? 1 : 0;
			if ($k['keywords'])
				array_push ($keys, explode (',', $k['keywords']));
			if ($k['description'])
				// ony get the first words from description of each post - they should be the most important ones
				array_push ($descriptions, trim (preg_replace ('/^(([^\s]+\s){' . $options['words_from_posts_in_aggregated_description'] . '}).*/', '$1', $k['description'])));
		}
		// we only set keywords and description if all posts are written in the same language
		// to prevent language mixes in meta informations
		if ($same_language == count ($posts)) {
			// get mixed list of the most important keywords
			$j = 0;
			$keywords = !$meta['keywords'] ? array () : explode (',', $meta['keywords']);
			do {
				$pushed = false;
				for ($i = 0; $i < count ($keys); $i++)
					if (count ($keywords) < $options['max_keywords_count'] && $keys[$i][$j]) {
						array_push ($keywords, $keys[$i][$j]);
						$pushed = true;
					}
				$j++;
			} while (count ($keywords) < $options['max_keywords_count'] && $pushed);
			$meta['keywords'] = implode (',', $keywords);
			if (!$meta['description']) {
				// generate description-tag from post-descriptions
				$description = implode ('...', $descriptions);
				if (strlen ($description) > $options['max_description_length'])
					$meta['description'] = substr ($description, 0, $options['max_description_length']);
				$meta['description'] = substr ($meta['description'], 0, strrpos ($meta['description'], ' '));
			}
		}
		else
			// drop language from metadata if posts are in different languages
			unset ($meta['lang']);
		// set robots-tag as configured
		if (!$meta['robots'])
			$meta['robots'] = ($options['index_aggregated'] ? 'index' : 'noindex') . ',' . ($options['follow_aggregated'] ? 'follow' : 'nofollow');
		// canonical url
		$canstruct = array ('type' => 'home');
		if (is_category ()) {
			$category_info = get_category ($wp_query->query_vars['cat']);
			$canstruct = array ('type' => 'category', 'id' => $wp_query->query_vars['cat'], 'name' => $wp_query->query['category_name'] ? $wp_query->query['category_name'] : $category_info->slug);
		}
		else if ($wp_query->query['tag'])
			$canstruct = array ('type' => 'tag', 'name' => $wp_query->query['tag']);
		else if ($wp_query->query['author_name'] || $wp_query->query['author']) {
			$user_data = get_userdata ($wp_query->query['author']);
			$canstruct = array ('type' => 'author', 'id' => $wp_query->query['author'], 'name' => $wp_query->query['author_name'] ? $wp_query->query['author_name'] : $user_data->user_login);
		}
		else if (($wp_query->query['year'] && $wp_query->query['monthnum'] && $wp_query->query['day']) || strlen ($wp_query->query['m']) == 8)
			$canstruct = array ('type' => 'archive', 'year' => $wp_query->query['year'] ? $wp_query->query['year'] : substr ($wp_query->query['m'], 0, 4), 'month' => $wp_query->query['monthnum'] ? $wp_query->query['monthnum'] : substr ($wp_query->query['m'], 4, 2), 'day' => $wp_query->query['day'] ? $wp_query->query['day'] : substr ($wp_query->query['m'], 6, 2));
		else if (($wp_query->query['year'] && $wp_query->query['monthnum']) || strlen ($wp_query->query['m']) == 6)
			$canstruct = array ('type' => 'archive', 'year' => $wp_query->query['year'] ? $wp_query->query['year'] : substr ($wp_query->query['m'], 0, 4), 'month' => $wp_query->query['monthnum'] ? $wp_query->query['monthnum'] : substr ($wp_query->query['m'], 4, 2));
		else if ($wp_query->query['year'] || strlen ($wp_query->query['m']) == 4)
			$canstruct = array ('type' => 'archive', 'year' => $wp_query->query['year'] ? $wp_query->query['year'] : substr ($wp_query->query['m'], 0, 4));
		// pagination
		if ($wp_query->query_vars['paged'])
			$canstruct = array_merge ($canstruct, array ('page' => $wp_query->query_vars['paged']));
		$meta['canonical'] = ks_canonical ($canstruct);
	} else if ($wp_query->post_count == 0) {
		// tags, categories and search with no posts
		$meta['robots'] = 'noindex,nofollow';
	}
	// drop old title-tag from output-buffer
	// if author has set one for a particular page or post this one will be inserted
	// moreover we append the page number if we are not on the first page
	if (($meta['title'] || $wp_query->query_vars['paged']) && !$options['testmode'] && $options['serve_title']) {
		$buffer = substr (ob_get_contents (), 0, stripos (ob_get_contents (), '<title>')) . substr (stristr (ob_get_contents (), '</title>'), 8);
		// append page number to the title
		if ($wp_query->query_vars['paged']) {
			$start = stripos (ob_get_contents (), '<title>') + 7;
			$end = stripos (ob_get_contents (), '</title>');
			if (!$meta['title'])
				$meta['title'] = substr (ob_get_contents (), $start, ($end - $start));
			// if title in aggregations has no pagination we add it automatically
			if (!$hasPagination)
				$meta['title'] .= ' ' . __('&ndash; page', 'keyword-statistics') . ' ' . $wp_query->query_vars['paged'];
		}
		ob_clean ();
		ob_start ();
		echo $buffer;
	}
	if ($meta['title'] && ($options['sitewide_title_predecessor'] || $options['sitewide_title_successor']))
		$meta['title'] = ks_replace_variables ($options['sitewide_title_predecessor']) . $meta['title'] . ks_replace_variables ($options['sitewide_title_successor']);
	if ($options['sitewide_keywords_predecessor'] && $meta['keywords']) {
		if (!is_page () && !is_single ()) {
			$kc = explode (',', $options['sitewide_keywords_predecessor']);
			$keywords = array_merge ($kc, array_splice (explode (',', $meta['keywords']), 0, -count ($kc)));
			$meta['keywords'] = implode (',', $keywords);
		}
		else
			$meta['keywords'] = $options['sitewide_keywords_predecessor'] . ',' . $meta['keywords'];
	}
	if ($options['sitewide_keywords_successor'] && $meta['keywords']) {
		if (!is_page () && !is_single ()) {
			$kc = explode (',', $options['sitewide_keywords_successor']);
			$keywords = array_merge (array_splice (explode (',', $meta['keywords']), 0, -count ($kc)), $kc);
			$meta['keywords'] = implode (',', $keywords);
		}
		else
			$meta['keywords'] = $meta['keywords'] . ',' . $options['sitewide_keywords_successor'];
	}
	if ($meta['description'] && ($options['sitewide_description_predecessor'] || $options['sitewide_description_successor']))
		$meta['description'] = ks_replace_variables ($options['sitewide_description_predecessor']) . $meta['description'] . ks_replace_variables ($options['sitewide_description_successor']);
	// Append sitewide settings for ODP, Yahoo! Directory and archiving
	// If robots is not already definded in the meta-array we should do this
	// to prevent problems with string assignment operator.
	if (!$meta['robots'])
		$meta['robots'] = '';
	// in some cases PHP seems to have a problem with using concatenated assignment operators on associative arrays
	$meta['robots'] = $meta['robots'] . ($options['noodp'] ? (strlen (trim ($meta['robots'])) > 0 ? ',' : '') . 'noodp' : '');
	$meta['robots'] = $meta['robots'] . ($options['noarchive'] ? (strlen (trim ($meta['robots'])) > 0 ? ',' : '') . 'noarchive' : '');
	$meta['robots'] = $meta['robots'] . ($options['noydir'] ? (strlen (trim ($meta['robots'])) > 0 ? ',' : '') . 'noydir' : '');
	echo ($options['serve_title'] == 1 && strlen ($meta['title']) > 0 ? '<title>' . $meta['title'] . '</title>' . "\n" : '') .
	     ($options['serve_meta_keywords'] == 1 && strlen (trim ($meta['keywords'])) > 0 ? '<meta name="keywords"' . (strlen ($meta['lang']) > 0 && $options['set_language_meta'] == 1 ? ' lang="' . $meta['lang'] . '"' : '') . ' content="' . $meta['keywords'] . '" />' . "\n" : '') .
	     ($options['serve_meta_description'] == 1 && strlen (trim ($meta['description'])) > 0 ? '<meta name="description"' . (strlen ($meta['lang']) > 0 && $options['set_language_meta'] == 1 ? ' lang="' . $meta['lang'] . '"' : '') . ' content="' . $meta['description'] . '" />' . "\n" : '') .
	     ($options['set_language_meta'] == 1 ? (strlen ($meta['lang']) > 0 ? '<meta name="language" content="' . $meta['lang'] . '" />' . "\n" . '<meta http-equiv="Content-Language" content="' . $meta['lang'] . '" />' . "\n" : '') : '').
	     ($options['serve_meta_robots'] == 1 && strlen (trim ($meta['robots'])) > 0 ? '<meta name="robots" content="' . $meta['robots'] . '" />' . "\n" : '') .
	     ($options['serve_meta_canonical'] == 1 && strlen ($meta['canonical']) > 0 ? '<link rel="canonical" href="' . strtolower ($meta['canonical']) . '" />' . "\n" : '') .
	     ($options['testmode'] ? '' : '<!--') . " end of metadata -->\n";
}
add_action ('wp_head', 'keyword_statistics_add_meta_tags');
// Remove action for canonical url generation added in WP versions 2.9+
// The plugin does this not only for pages and posts but also for all kinds of content aggregations
remove_action ( 'wp_head', 'rel_canonical' );

// update the metadata
function keyword_statistics_update_metadata () {
	global $post;

	// don't change meta informations if we are autosaving
	if (!isset ($_POST['autosave'])) {
		// in quick edit mode we have to leave already defined metadata (if there are some)
		if (!isset ($_POST['kslang']))
			// let's get out here without doing anything with meta informations
			return;
		if (!wp_verify_nonce ($_POST['ks_update_meta_nonce'], 'ks_update_meta_nonce'))
			die (__('Nonce verification failed:', 'keyword-statistics') . ' ' . __('Error while updating the meta informations for the post!', 'keyword-statistics'));
		$robots = (!isset ($_POST['ksrobotindex']) ? 'noindex' : ($_POST['ksrobotindex'] == 'index' || $_POST['ksrobotindex'] == 'ks_ri' ? 'index' : 'noindex')) . ',' .
			  (!isset ($_POST['ksrobotfollow']) ? 'nofollow' : ($_POST['ksrobotfollow'] == 'follow' || $_POST['ksrobotfollow'] == 'ks_rf' ? 'follow' : 'nofollow'));
		//if (!function_exists ('apply_filters'))
			$meta = array ('lang' => trim (htmlspecialchars (strip_tags ($_POST['kslang']))),
				       'keywords' => preg_replace ('/,+/', ',', preg_replace ('/ *, */', ',', preg_replace ('/ +/', ' ', preg_replace ('/,+/', ',', trim (htmlspecialchars (strip_tags ($_POST['kskeywords'])), ' ,'))))),
				       'keywords_autoupdate' => $_POST['keywords_autoupdate'] ? 1 : 0,
				       'description' => trim (htmlspecialchars (strip_tags ($_POST['ksdescription']))),
				       'description_autoupdate' => $_POST['description_autoupdate'] ? 1 : 0,
				       'title' => trim (htmlspecialchars (strip_tags ($_POST['kstitle']))),
				       'robots' => $robots);
		/*else
		max length, if autoupdate is activated
			$meta = array ('lang' => $_POST['kslang'],
				       'keywords' => apply_filters ('the_title_rss', $_POST['kskeywords']),
				       'keywords_autoupdate' => $_POST['keywords_autoupdate'],
				       'description' => apply_filters ('the_title_rss', $_POST['ksdescription']),
				       'description_autoupdate' => $_POST['description_autoupdate'],
				       'title' => apply_filters ('the_title_rss', $_POST['kstitle']),
				       'robots' => $robots);*/
		add_post_meta ($_POST['post_ID'], 'ks_metadata', $meta, true) or update_post_meta ($_POST['post_ID'], 'ks_metadata', $meta);
	}
}
add_action ('save_post', 'keyword_statistics_update_metadata');

// Plugin options
function init_keyword_statistics_settings () {
	global $ks_plugin_version;
	if (function_exists ('register_setting')) {
		// for WP 2.7+
		if (!get_option('keyword_statistics_configuration')) {
			// set default or import configuration
			$default_configuration = array (
				'version' => get_option('ks_version') ? get_option('ks_version') : $ks_plugin_version,
				'testmode' => get_option('ks_testmode') ? get_option('ks_testmode') : 0,
				'default_language' => get_option('ks_default_language') ? get_option('ks_default_language') : 'en',
				'set_language_meta' => get_option('ks_set_language_meta') ? get_option('ks_set_language_meta') : 0,
				'filter_stopwords' => get_option('ks_filter_stopwords') ? get_option('ks_filter_stopwords') : 1,
				'max_list_items' => get_option('ks_max_list_items') ? get_option('ks_max_list_items') : 5,
				'automatic_update' => get_option('ks_automatic_update') ? get_option('ks_automatic_update') : 1,
				'update_interval' => get_option('ks_update_interval') ? get_option('ks_update_interval') : 5,
				'2word_phrases' => get_option('ks_2word_phrases') ? get_option('ks_2word_phrases') : 1,
				'3word_phrases' => get_option('ks_3word_phrases') ? get_option('ks_3word_phrases') : 1,
				'meta_keywords_count' => get_option('ks_meta_keywords_count') ? get_option('ks_meta_keywords_count') : 8,
				'max_keywords_count' => get_option('ks_max_keywords_count') ? get_option('ks_max_keywords_count') : 12,
				'max_keywords_length' => get_option('ks_max_keywords_length') ? get_option('ks_keywords_length') : 80,
				'index_aggregated' => get_option('ks_index_aggregated') ? get_option('ks_index_aggregated') : 0,
				'follow_aggregated' => get_option('ks_follow_aggregated') ? get_option('ks_follow_aggregated') : 1,
				'noodp' => get_option('ks_noodp') ? get_option('ks_noodp') : 1,
				'noydir' => get_option('ks_noydir') ? get_option('ks_noydir') : 1,
				'noarchive' => get_option('ks_noarchive') ? get_option('ks_noarchive') : 1,
				'dont_serve_metadata' => get_option('ks_dont_serve_metadata') ? get_option('ks_dont_serve_metadata') : 0,
				'serve_title' => get_option('ks_serve_title') ? get_option('ks_serve_title') : 1,
				'serve_meta_robots' => get_option('ks_serve_meta_robots') ? get_option('ks_serve_meta_robots') : 1,
				'serve_meta_keywords' => get_option('ks_serve_meta_keywords') ? get_option('ks_serve_meta_keywords') : 1,
				'serve_meta_description' => get_option('ks_serve_meta_description') ? get_option('ks_serve_meta_description') : 1,
				'serve_meta_canonical' => get_option('ks_serve_meta_canonical') ? get_option('ks_serve_meta_canonical') : 1,
				'words_from_posts_in_aggregated_description' => get_option('ks_words_from_posts_in_aggregated_description') ? get_option('ks_words_from_posts_in_aggregated_description') : 4,
				'max_title_length' => get_option('ks_max_title_length') ? get_option('ks_max_title_length') : 60,
				'min_description_length' => get_option('ks_min_description_length') ? get_option('ks_min_description_length') : 80,
				'max_description_length' => get_option('ks_max_description_length') ? get_option('ks_max_description_length') : 160,
				'automatic_meta_data_update' => get_option('ks_automatic_meta_data_update') ? get_option('ks_automatic_meta_data_update') : 1,
				'min_words' => get_option('ks_min_words') ? get_option('ks_min_words') : 50,
				'hide_fields_authors_cant_change' => get_option('ks_hide_fields_authors_cant_change') ? get_option('ks_hide_fields_authors_cant_change') : 1,
				'hide_fields_authors_cant_change_from_admin' => get_option('ks_hide_fields_authors_cant_change_from_admin') ? get_option('ks_hide_fields_authors_cant_change_from_admin') : 0,
				'authors_can_change_content_language' => get_option('ks_authors_can_change_content_language') ? get_option('ks_authors_can_change_content_language') : 0,
				'authors_can_disable_stopword_filter' => get_option('ks_authors_can_disable_stopword_filter') ? get_option('ks_authors_can_disable_stopword_filter') : 0,
				'authors_can_set_individual_title' => get_option('ks_authors_can_set_individual_title') ? get_option('ks_authors_can_set_individual_title') : 1,
				'authors_can_edit_keywords' => get_option('ks_authors_can_edit_keywords') ? get_option('ks_authors_can_edit_keywords') : 0,
				'authors_can_edit_description' => get_option('ks_authors_can_edit_description') ? get_option('ks_authors_can_edit_description') : 0,
				'authors_can_control_robots' => get_option('ks_authors_can_control_robots') ? get_option('ks_authors_can_control_robots') : 0,
				'home_first' => get_option('ks_home_first') ? get_option('ks_home_first') : 0,
				'category_first' => get_option('ks_category_first') ? get_option('ks_category_first') : 0,
				'tag_first' => get_option('ks_tag_first') ? get_option('ks_tag_first') : 0,
				'archive_first' => get_option('ks_archive_first') ? get_option('ks_archive_first') : 0,
				'author_first' => get_option('ks_author_first') ? get_option('ks_author_first') : 0,
				'search_first' => get_option('ks_search_first') ? get_option('ks_search_first') : 0,
				'home_index' => get_option('ks_home_index') ? get_option('ks_home_index') : 'default',
				'category_index' => get_option('ks_category_index') ? get_option('ks_category_index') : 'default',
				'tag_index' => get_option('ks_tag_index') ? get_option('ks_tag_index') : 'default',
				'archive_index' => get_option('ks_archive_index') ? get_option('ks_archive_index') : 'default',
				'author_index' => get_option('ks_author_index') ? get_option('ks_author_index') : 'default',
				'search_index' => get_option('ks_search_index') ? get_option('ks_search_index') : 'default',
				'home_follow' => get_option('ks_home_follow') ? get_option('ks_home_follow') : 'default',
				'category_follow' => get_option('ks_category_follow') ? get_option('ks_category_follow') : 'default',
				'tag_follow' => get_option('ks_tag_follow') ? get_option('ks_tag_follow') : 'default',
				'archive_follow' => get_option('ks_archive_follow') ? get_option('ks_archive_follow') : 'default',
				'author_follow' => get_option('ks_author_follow') ? get_option('ks_author_follow') : 'default',
				'search_follow' => get_option('ks_search_follow') ? get_option('ks_search_follow') : 'default',
				'feeds_noindex' => get_option('ks_feeds_noindex') ? get_option('ks_feeds_noindex') : 0
			);
			add_option ('keyword_statistics_configuration', $default_configuration);
			// drop older configurations
			delete_option ('ks_version');
			delete_option ('ks_testmode');
			delete_option ('ks_default_language');
			delete_option ('ks_set_language_meta');
			delete_option ('ks_filter_stopwords');
			delete_option ('ks_max_list_items');
			delete_option ('ks_automatic_update');
			delete_option ('ks_update_interval');
			delete_option ('ks_2word_phrases');
			delete_option ('ks_3word_phrases');
			delete_option ('ks_meta_keywords_count');
			delete_option ('ks_max_keywords_count');
			delete_option ('ks_keywords_length');
			delete_option ('ks_index_aggregated');
			delete_option ('ks_follow_aggregated');
			delete_option ('ks_noodp');
			delete_option ('ks_noydir');
			delete_option ('ks_noarchive');
			delete_option ('ks_dont_serve_metadata');
			delete_option ('ks_serve_title');
			delete_option ('ks_serve_meta_robots');
			delete_option ('ks_serve_meta_keywords');
			delete_option ('ks_serve_meta_description');
			delete_option ('ks_serve_meta_canonical');
			delete_option ('ks_words_from_posts_in_aggregated_description');
			delete_option ('ks_max_title_length');
			delete_option ('ks_min_description_length');
			delete_option ('ks_max_description_length');
			delete_option ('ks_automatic_meta_data_update');
			delete_option ('ks_min_words');
			delete_option ('ks_hide_fields_authors_cant_change');
			delete_option ('ks_hide_fields_authors_cant_change_from_admin');
			delete_option ('ks_authors_can_change_content_language');
			delete_option ('ks_authors_can_disable_stopword_filter');
			delete_option ('ks_authors_can_set_individual_title');
			delete_option ('ks_authors_can_edit_keywords');
			delete_option ('ks_authors_can_edit_description');
			delete_option ('ks_authors_can_control_robots');
			delete_option ('ks_home_first');
			delete_option ('ks_category_first');
			delete_option ('ks_tag_first');
			delete_option ('ks_archive_first');
			delete_option ('ks_author_first');
			delete_option ('ks_search_first');
			delete_option ('ks_home_index');
			delete_option ('ks_category_index');
			delete_option ('ks_tag_index');
			delete_option ('ks_archive_index');
			delete_option ('ks_author_index');
			delete_option ('ks_search_index');
			delete_option ('ks_home_follow');
			delete_option ('ks_category_follow');
			delete_option ('ks_tag_follow');
			delete_option ('ks_archive_follow');
			delete_option ('ks_author_follow');
			delete_option ('ks_search_follow');
			delete_option ('ks_feeds_noindex');
		}
		register_setting ('plugin_options', 'keyword_statistics_configuration');
	}
	else {
		// and for older versions
		if (!get_option('ks_default_language')) {
			add_option ('ks_version', $ks_plugin_version);
			add_option ('ks_testmode', 0);
			add_option ('ks_default_language', 'en');
			add_option ('ks_set_language_meta', 0);
			add_option ('ks_filter_stopwords', 1);
			add_option ('ks_max_list_items', 5);
			add_option ('ks_automatic_update', 1);
			add_option ('ks_update_interval', 5);
			add_option ('ks_2word_phrases', 1);
			add_option ('ks_3word_phrases', 1);
			add_option ('ks_meta_keywords_count', 8);
			add_option ('ks_max_keywords_count', 12);
			add_option ('ks_keywords_length', 80);
			add_option ('ks_index_aggregated', 0);
			add_option ('ks_follow_aggregated', 1);
			add_option ('ks_noodp', 1);
			add_option ('ks_noydir', 1);
			add_option ('ks_noarchive', 1);
			add_option ('ks_dont_serve_metadata', 0);
			add_option ('ks_serve_title', 1);
			add_option ('ks_serve_meta_robots', 1);
			add_option ('ks_serve_meta_keywords', 1);
			add_option ('ks_serve_meta_description', 1);
			add_option ('ks_serve_meta_canonical', 1);
			add_option ('ks_words_from_posts_in_aggregated_description', 4);
			add_option ('ks_max_title_length', 60);
			add_option ('ks_min_description_length', 80);
			add_option ('ks_max_description_length', 160);
			add_option ('ks_automatic_meta_data_update', 1);
			add_option ('ks_hide_fields_authors_cant_change', 1);
			add_option ('ks_hide_fields_authors_cant_change_from_admin', 0);
			add_option ('ks_min_words', 50);
			add_option ('ks_authors_can_change_content_language', 0);
			add_option ('ks_authors_can_disable_stopword_filter', 0);
			add_option ('ks_authors_can_set_individual_title', 1);
			add_option ('ks_authors_can_edit_keywords', 0);
			add_option ('ks_authors_can_edit_description', 0);
			add_option ('ks_authors_can_control_robots', 0);
			add_option ('ks_home_first', 0);
			add_option ('ks_category_first', 0);
			add_option ('ks_tag_first', 0);
			add_option ('ks_archive_first', 0);
			add_option ('ks_author_first', 0);
			add_option ('ks_search_first', 0);
			add_option ('ks_home_index', 'default');
			add_option ('ks_category_index', 'default');
			add_option ('ks_tag_index', 'default');
			add_option ('ks_archive_index', 'default');
			add_option ('ks_author_index', 'default');
			add_option ('ks_search_index', 'default');
			add_option ('ks_home_follow', 'default');
			add_option ('ks_category_follow', 'default');
			add_option ('ks_tag_follow', 'default');
			add_option ('ks_archive_follow', 'default');
			add_option ('ks_author_follow', 'default');
			add_option ('ks_search_follow', 'default');
			add_option ('ks_feeds_noindex', 0);
		}
	}
	// add new options to the configuration without changing the way the plugin acted in the past
	$options = keyword_statistics_get_plugin_configuration ();
	// new option in 1.2.5
	if (!$options['min_words'])
		if (function_exists ('register_setting')) {
			$options['min_words'] = 50;
			update_option ('keyword_statistics_configuration', $options);
		}
		else
			add_option ('ks_min_words', 50);
	// new option in 1.2.7
	if (substr ($options['version'], 0, 5) < '1.2.7')
		if (function_exists ('register_setting')) {
			$options['serve_meta_robots'] = 1;
			update_option ('keyword_statistics_configuration', $options);
		}
		else
			add_option ('ks_serve_meta_robots', 1);
	// new option in 1.2.9
	if (substr ($options['version'], 0, 5) < '1.2.9')
		if (function_exists ('register_setting')) {
			$options['dont_serve_metadata'] = 0;
			update_option ('keyword_statistics_configuration', $options);
		}
		else
			add_option ('ks_dont_serve_metadata', 0);
	// new option in 1.3.1
	if (substr ($options['version'], 0, 5) < '1.3.1')
		if (function_exists ('register_setting')) {
			$options['testmode'] = 0;
			update_option ('keyword_statistics_configuration', $options);
		}
		else
			add_option ('ks_testmode', 0);
	// new options in 1.3.5
	if (substr ($options['version'], 0, 5) < '1.3.5')
		if (function_exists ('register_setting')) {
			$options['serve_meta_keywords'] = 1;
			$options['serve_meta_description'] = 1;
			update_option ('keyword_statistics_configuration', $options);
		}
		else {
			add_option ('ks_serve_meta_keywords', 1);
			add_option ('ks_serve_meta_description', 1);
		}
	// new options in 1.3.7
	if (substr ($options['version'], 0, 5) < '1.3.7')
		if (function_exists ('register_setting')) {
			$options['serve_meta_canonical'] = 1;
			update_option ('keyword_statistics_configuration', $options);
		}
		else
			add_option ('ks_serve_meta_canonical', 1);
	// new options in 1.3.9
	if (substr ($options['version'], 0, 5) < '1.3.9')
		if (function_exists ('register_setting')) {
			$options['serve_title'] = 1;
			update_option ('keyword_statistics_configuration', $options);
		}
		else
			add_option ('ks_serve_title', 1);
	// new option in 1.4.3
	if (!$options['max_title_length'])
		if (function_exists ('register_setting')) {
			$options['max_title_length'] = 60;
			update_option ('keyword_statistics_configuration', $options);
		}
		else
			add_option ('ks_max_title_length', 60);
	// new option in 1.4.5
	if (!$options['max_keywords_length'])
		if (function_exists ('register_setting')) {
			$options['max_keywords_count'] = 12;
			$options['max_keywords_length'] = 80;
			update_option ('keyword_statistics_configuration', $options);
		}
		else {
			add_option ('ks_max_keywords_count', 12);
			add_option ('ks_max_keywords_length', 80);
		}
	// new options since 1.5.8
	if (!isset ($options['home_index'])) {
		$options['home_first'] = $options['category_first'] = $options['tag_first'] = $options['archive_first'] = $options['author_first'] = $options['search_first'] = 0;
		$options['home_index'] = $options['category_index'] = $options['tag_index'] = $options['archive_index'] = $options['author_index'] = $options['search_index'] = 'default';
		$options['home_follow'] = $options['category_follow'] = $options['tag_follow'] = $options['archive_follow'] = $options['author_follow'] = $options['search_follow'] = 'default';
		keyword_statistics_set_plugin_configuration ($options);
	}
	// initialize the metadata for content aggregations integrated in 1.5.8
	if (!isset ($options['home_title'])) {
		$options['home_title'] = $options['category_title'] = $options['tag_title'] = $options['archive_title'] = $options['author_title'] = $options['search_title'] = '';
		$options['home_description'] = $options['category_description'] = $options['tag_description'] = $options['archive_description'] = $options['author_description'] = $options['search_description'] = '';
		$options['home_keywords'] = $options['category_keywords'] = $options['tag_keywords'] = $options['archive_keywords'] = $options['author_keywords'] = $options['search_keywords'] = '';
		keyword_statistics_set_plugin_configuration ($options);
	}
	// since 1.6.1: initialization of new sitewide meta informations
	if (!isset ($options['sitewide_title_predecessor'])) {
		$options['sitewide_title_predecessor'] = $options['sitewide_title_successor'] = '';
		$options['sitewide_keywords_predecessor'] = $options['sitewide_keywords_successor'] = '';
		$options['sitewide_description_predecessor'] = $options['sitewide_description_successor'] = '';
		keyword_statistics_set_plugin_configuration ($options);
	}
	// new options in 1.7.6
	if (substr ($options['version'], 0, 5) < '1.7.6') {
		$options['feeds_noindex'] = 0;
		keyword_statistics_set_plugin_configuration ($options);
	}
	// if there is still no version information or we are running a new one we set the current
	if (!isset ($options['version']) || $options['version'] != $ks_plugin_version)
		if (function_exists ('register_setting')) {
			$options['version'] = $ks_plugin_version;
			update_option ('keyword_statistics_configuration', $options);
		}
		else
			add_option ('ks_version', $ks_plugin_version);
}

if (is_admin ()) {
	add_action ('admin_init', 'init_keyword_statistics_settings');
}

function is_author_not_admin () {
	global $current_user;
	$rval = isset ($current_user->caps['author']) && $current_user->caps['author'] == 1 && (!isset ($current_user->caps['administrator']) || $current_user->caps['administrator'] != 1);
	return $rval;
}

function is_contributor () {
	global $current_user;
	return isset ($current_user->caps['contributor']) && $current_user->caps['contributor'] == 1 && (!isset ($current_user->caps['administrator']) || $current_user->caps['administrator'] != 1);
}

// Plugin Output
function post_keyword_statistics () {
	global $post;
	$meta = get_post_meta ($post->ID, 'ks_metadata', true);
	$options = keyword_statistics_get_plugin_configuration (); ?>
	<input type="hidden" name="ks_update_meta_nonce" value="<?php echo wp_create_nonce ('ks_update_meta_nonce') ?>" />
	<table class="ksmeta">
		<tr>
			<td>
				<label for="kslang"><?php _e('Language', 'keyword-statistics') ?>:</label>
				<?php if (((is_author_not_admin() || is_contributor()) && intval ($options['authors_can_change_content_language']) != 1)) { ?>
				<input type="hidden" name="kslang" id="kslang" value="<?php echo $meta['lang'] ? $meta['lang'] : $options['default_language'] ?>" />
				<select disabled="disabled" id="kslang_view" name="kslang_view" onchange="updateTextInfo()">
					<option value="en" <?php echo $meta['lang'] == 'en' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'en' ? 'selected="selected"' : '' ?>>en</option>
					<option value="bg" <?php echo $meta['lang'] == 'bg' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'bg' ? 'selected="selected"' : '' ?>>bg</option>
					<option value="cs-cz" <?php echo $meta['lang'] == 'cs-cz' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'cs-cz' ? 'selected="selected"' : '' ?>>cs-cz</option>
					<option value="da" <?php echo $meta['lang'] == 'da' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'da' ? 'selected="selected"' : '' ?>>da</option>
					<option value="de" <?php echo $meta['lang'] == 'de' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'de' ? 'selected="selected"' : '' ?>>de</option>
					<option value="es" <?php echo $meta['lang'] == 'es' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'es' ? 'selected="selected"' : '' ?>>es</option>
					<option value="fr" <?php echo $meta['lang'] == 'fr' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'fr' ? 'selected="selected"' : '' ?>>fr</option>
					<option value="hu" <?php echo $meta['lang'] == 'hu' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'hu' ? 'selected="selected"' : '' ?>>hu</option>
					<option value="nl" <?php echo $meta['lang'] == 'nl' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'nl' ? 'selected="selected"' : '' ?>>nl</option>
					<option value="pl" <?php echo $meta['lang'] == 'pl' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'pl' ? 'selected="selected"' : '' ?>>pl</option>
					<option value="pt-br" <?php echo $meta['lang'] == 'pt-br' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'pt-br' ? 'selected="selected"' : '' ?>>pt-br</option>
					<option value="sk" <?php echo $meta['lang'] == 'sk' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'sk' ? 'selected="selected"' : '' ?>>sk</option>
					<option value="tr" <?php echo $meta['lang'] == 'tr' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'tr' ? 'selected="selected"' : '' ?>>tr</option>
				</select>
				<?php } else { ?>
				<select id="kslang" name="kslang" onchange="updateTextInfo()">
					<option value="en" <?php echo $meta['lang'] == 'en' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'en' ? 'selected="selected"' : '' ?>>en</option>
					<option value="bg" <?php echo $meta['lang'] == 'bg' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'bg' ? 'selected="selected"' : '' ?>>bg</option>
					<option value="cs-cz" <?php echo $meta['lang'] == 'cs-cz' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'cs-cz' ? 'selected="selected"' : '' ?>>cs-cz</option>
					<option value="da" <?php echo $meta['lang'] == 'da' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'da' ? 'selected="selected"' : '' ?>>da</option>
					<option value="de" <?php echo $meta['lang'] == 'de' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'de' ? 'selected="selected"' : '' ?>>de</option>
					<option value="es" <?php echo $meta['lang'] == 'es' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'es' ? 'selected="selected"' : '' ?>>es</option>
					<option value="fr" <?php echo $meta['lang'] == 'fr' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'fr' ? 'selected="selected"' : '' ?>>fr</option>
					<option value="hu" <?php echo $meta['lang'] == 'hu' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'hu' ? 'selected="selected"' : '' ?>>hu</option>
					<option value="nl" <?php echo $meta['lang'] == 'nl' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'nl' ? 'selected="selected"' : '' ?>>nl</option>
					<option value="pl" <?php echo $meta['lang'] == 'pl' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'pl' ? 'selected="selected"' : '' ?>>pl</option>
					<option value="pt-br" <?php echo $meta['lang'] == 'pt-br' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'pt-br' ? 'selected="selected"' : '' ?>>pt-br</option>
					<option value="sk" <?php echo $meta['lang'] == 'sk' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'sk' ? 'selected="selected"' : '' ?>>sk</option>
					<option value="tr" <?php echo $meta['lang'] == 'tr' ? 'selected="selected"' : !$meta['lang'] && $options['default_language'] == 'tr' ? 'selected="selected"' : '' ?>>tr</option>
				</select>
				<?php } ?>
			</td>
			<td>
				<label for="ksfilter"><?php _e('Filter stopwords', 'keyword-statistics') ?>:</label>
				<input <?php echo ((is_author_not_admin() || is_contributor()) && intval ($options['authors_can_disable_stopword_filter']) != 1 ? 'disabled="disabled"' : '') ?>type="checkbox" name="ksfilter" id="ksfilter" onchange="updateTextInfo()" value="ks_filter" <?php echo $options['filter_stopwords'] == 1 ? 'checked="checked"' : '' ?> />
			</td>
			<td>
				<label for="kslines"><?php _e('Show first', 'keyword-statistics') ?>:</label>
				<select id="kslines" name="kslines" onchange="updateTextInfo()">
					<?php for ($i = 1; $i < 11; $i++) echo '<option value="' . $i . '" ' . ($options['max_list_items'] == $i ? 'selected="selected"' : '') . '>' . $i . '</option>'; ?>
				</select>
			</td>
			<td><div class="submit"><input class="right" type="button" name="ksupdate" onclick="updateTextInfo()" value=" <?php _e('Update') ?> "/></div></td>
		</tr>
	</table>
	<?php if ($options['automatic_update']) echo '<script type="text/javascript">var ks_updateInterval = window.setInterval (\'updateTextInfo()\', ' . $options['update_interval'] . '000);</script>' ?>
	<hr <?php echo ((((is_author_not_admin() || is_contributor()) && $options['hide_fields_authors_cant_change']) || (is_admin() && $options['hide_fields_authors_cant_change_from_admin'])) && intval ($options['authors_can_set_individual_title']) != 1 && intval ($options['authors_can_edit_keywords']) != 1 && intval ($options['authors_can_control_robots']) != 1 && intval ($options['authors_can_edit_description']) != 1 ? ' style="visibility:hidden;display:none"' : '') ?> />
	<table class="ksmeta">
		<tr<?php echo ((((is_author_not_admin() || is_contributor()) && $options['hide_fields_authors_cant_change']) || (is_admin() && $options['hide_fields_authors_cant_change_from_admin'])) && intval ($options['authors_can_set_individual_title']) != 1 ? ' style="visibility:hidden;display:none"' : '') ?>>
			<td style="vertical-align:top"><label for="kstitle"><?php _e('Title', 'keyword-statistics') ?>:</label></td>
			<td>
				<?php if (((is_author_not_admin() || is_contributor()) && intval ($options['authors_can_set_individual_title']) != 1)) { ?>
				<input type="hidden" name="kstitle" id="kstitle" value="<?php echo isset ($meta['title']) ? $meta['title'] : '' ?>" />
				<textarea disabled="disabled" cols="50" rows="3" name="kstitle_view" id="kstitle_view"><?php echo isset ($meta['title']) ? $meta['title'] : '' ?></textarea>
				<?php } else { ?>
				<textarea onkeyup="fieldStatus(this)" style="width:99%" _cols="50" rows="3" name="kstitle" id="kstitle"><?php echo isset ($meta['title']) ? $meta['title'] : '' ?></textarea><br/>
				<span id="kstitle_status"></span>
				<?php } ?>
			</td>
		</tr>
		<tr<?php echo ((((is_author_not_admin() || is_contributor()) && $options['hide_fields_authors_cant_change']) || (is_admin() && $options['hide_fields_authors_cant_change_from_admin'])) && intval ($options['authors_can_edit_keywords']) != 1 ? ' style="visibility:hidden;display:none"' : '') ?>>
			<td style="vertical-align:top"><label for="kskeywords"><?php _e('Keywords', 'keyword-statistics') ?>:</label></td>
			<td>
				<?php if (((is_author_not_admin() || is_contributor()) && intval ($options['authors_can_edit_keywords']) != 1)) { ?>
				<input type="hidden" name="kskeywords" id="kskeywords" value="<?php echo isset ($meta['keywords']) ? $meta['keywords'] : '' ?>" />
				<input size="50" disabled="disabled" type="text" name="kskeywords_view" id="kskeywords_view" value="<?php echo isset ($meta['keywords']) ? $meta['keywords'] : '' ?>" />
				<input type="hidden" name="keywords_autoupdate" id="keywords_autoupdate" value="<?php echo (($post->filter == 'edit' && intval ($meta['keywords_autoupdate']) == 1) || (($post->filter != 'edit' || !$meta) && $options['automatic_meta_data_update']) ? 'ks_kau' : '') ?>" />
				<?php } else { ?>
				<input onkeyup="fieldStatus(this)" style="width:99%" _size="50" type="text" name="kskeywords" id="kskeywords" value="<?php echo isset ($meta['keywords']) ? $meta['keywords'] : '' ?>" /><br/>
				<span id="kskeywords_status"></span><br/>
				<input type="checkbox" name="keywords_autoupdate" id="keywords_autoupdate" onchange="updateTextInfo()" value="ks_kau" <?php echo (($post->filter == 'edit' && intval ($meta['keywords_autoupdate']) == 1) || (($post->filter != 'edit' || !$meta) && $options['automatic_meta_data_update']) ? 'checked="checked"' : '') ?> /> <?php _e('use generated keywords', 'keyword-statistics') ?>
				<?php } ?>
			</td>
		</tr>
		<tr<?php echo ((((is_author_not_admin() || is_contributor()) && $options['hide_fields_authors_cant_change']) || (is_admin() && $options['hide_fields_authors_cant_change_from_admin'])) && intval ($options['authors_can_control_robots']) != 1 ? ' style="visibility:hidden;display:none"' : '') ?>>
			<td><label for="ksrobotfollow"><?php _e('Robots', 'keyword-statistics') ?>:</label></td>
			<td>
				<?php if (((is_author_not_admin() || is_contributor()) && intval ($options['authors_can_control_robots']) != 1)) { ?>
				<input type="hidden" name="ksrobotindex" id="ksrobotindex" value="<?php echo (preg_match ('/noindex/', $meta['robots']) ? 'noindex' : 'index') ?>" />
				<input disabled="disabled" type="checkbox" name="ksrobotindex_view" id="ksrobotindex_view" value="ks_ri" <?php echo preg_match ('/noindex/', $meta['robots']) ? '' : 'checked="checked"' ?> />index
				<input type="hidden" name="ksrobotfollow" id="ksrobotfollow" value="<?php echo (preg_match ('/nofollow/', $meta['robots']) ? 'nofollow' : 'follow') ?>" />
				<input disabled="disabled" type="checkbox" name="ksrobotfollow_view" id="ksrobotfollow_view" value="ks_rf" <?php echo preg_match ('/nofollow/', $meta['robots']) ? '' : 'checked="checked"' ?> />follow
				<?php } else { ?>
				<input type="checkbox" name="ksrobotindex" id="ksrobotindex" value="ks_ri" <?php echo preg_match ('/noindex/', $meta['robots']) ? '' : 'checked="checked"' ?> />index
				<input type="checkbox" name="ksrobotfollow" id="ksrobotfollow" value="ks_rf" <?php echo preg_match ('/nofollow/', $meta['robots']) ? '' : 'checked="checked"' ?> />follow
				<?php } ?>
			</td>
		</tr>
		<tr<?php echo ((((is_author_not_admin() || is_contributor()) && $options['hide_fields_authors_cant_change']) || (is_admin() && $options['hide_fields_authors_cant_change_from_admin'])) && intval ($options['authors_can_edit_description']) != 1 ? ' style="visibility:hidden;display:none"' : '') ?>>
			<td style="vertical-align:top"><label for="ksdescription"><?php _e('Description', 'keyword-statistics') ?>:</label></td>
			<td>
				<?php if (((is_author_not_admin() || is_contributor()) && intval ($options['authors_can_edit_description']) != 1)) { ?>
				<input type="hidden" name="ksdescription" id="ksdescription" value="<?php echo isset ($meta['description']) ? $meta['description'] : '' ?>" />
				<textarea disabled="disabled" cols="50" rows="3" name="ksdescription_view" id="ksdescription_view"><?php echo isset ($meta['description']) ? $meta['description'] : '' ?></textarea>
				<input type="hidden" name="description_autoupdate" id="description_autoupdate" value="<?php echo (($post->filter == 'edit' && intval ($meta['description_autoupdate']) == 1) || (($post->filter != 'edit' || !$meta) && $options['automatic_meta_data_update']) ? 'ks_dau' : '') ?>" />
				<?php } else { ?>
				<textarea onkeyup="fieldStatus(this)" style="width:99%" _cols="50" rows="3" name="ksdescription" id="ksdescription"><?php echo isset ($meta['description']) ? $meta['description'] : '' ?></textarea><br/>
				<span id="ksdescription_status"></span><br/>
				<input type="checkbox" name="description_autoupdate" id="description_autoupdate" onchange="updateTextInfo()" value="ks_dau" <?php echo (($post->filter == 'edit' && intval ($meta['description_autoupdate']) == 1) || (($post->filter != 'edit' || !$meta) && $options['automatic_meta_data_update']) ? 'checked="checked"' : '') ?> /> <?php _e('use generated description', 'keyword-statistics') ?>
				<?php } ?>
			</td>
		</tr>
	</table>
	<script type="text/javascript">
	/* <![CDATA[ */
	// check if the strings in meta fields face the restrictions of configuration
	function fieldStatus (element) {
		var rex = /^ks([a-z]+)$/;
		if (rex.exec (element.id)) {
			var type = RegExp.$1, status = '';
			switch (type) {
				case 'title':
					ptitle = '<?php echo $options['sitewide_title_predecessor'] ?>' + element.value + '<?php echo $options['sitewide_title_successor'] ?>';
					status = ptitle.length + '/' + '<?php echo $options['max_title_length'] . ' ' . __('characters used', 'keyword-statistics') ?>';
					if (ptitle.length > <?php echo $options['max_title_length'] ?>)
						status = '<span style="color:red">' + status + '</span>';
					<?php if (strlen ($options['sitewide_title_predecessor']) != 0 || strlen ($options['sitewide_title_successor']) != 0) { ?>
					status = status + '<br/><?php echo __('Preview', 'keyword-statistics') ?>: ' + '<span style="color:#888"><?php echo $options['sitewide_title_predecessor'] ?></span>' + element.value + '<span style="color:#888"><?php echo $options['sitewide_title_successor'] ?></span>';
					<?php } ?>
					break;
				case 'keywords':
					k = element.value.replace(/^[, ]+/, '').replace(/[, ]+$/, '').replace(/,+/, ',').replace(/ +/g, ' ').replace(/ *, */g, ',').replace(/,+/g, ',');
					pk = '<?php echo sizeof ($options['sitewide_keywords_predecessor']) != 0 ? $options['sitewide_keywords_predecessor'] . "," : "" ?>' + element.value + '<?php echo sizeof ($options['sitewide_keywords_successor']) != 0 ? "," . $options['sitewide_keywords_successor'] : "" ?>';
					status = pk.length + '/' + '<?php echo $options['max_keywords_length'] . ' ' . __('characters', 'keyword-statistics') ?>';
					var keys = pk.split (',');
					for (var i = 0; i < keys.length; i++)
						if (keys[i] == '')
							keys.splice (i, 1);
					status = status + ' - ' + keys.length + '/' + '<?php echo $options['max_keywords_count'] . ' ' . __('keywords / phrases used', 'keyword-statistics') ?>';
					if (pk.length > <?php echo $options['max_keywords_length'] ?> || keys.length > <?php echo $options['max_keywords_count'] ?>)
						status = '<span style="color:red">' + status + '</span>';
					<?php if (strlen ($options['sitewide_keywords_predecessor']) != 0 || strlen ($options['sitewide_keywords_successor']) != 0) { ?>
					status = status + '<br/><?php echo __('Preview', 'keyword-statistics') ?>: ' + '<span style="color:#888"><?php echo sizeof ($options['sitewide_keywords_predecessor']) != 0 ? $options['sitewide_keywords_predecessor'] . "," : "" ?></span>' + element.value.replace (/,/g, ',<span style="font-size:1px"> </span>') + '<span style="color:#888"><?php echo sizeof ($options['sitewide_keywords_successor']) != 0 ? "," . $options['sitewide_keywords_successor'] : "" ?></span>';
					<?php } ?>
					break;
				case 'description':
					pdesc = '<?php echo $options['sitewide_description_predecessor'] ?>' + element.value + '<?php echo $options['sitewide_description_successor'] ?>';
					status = pdesc.length + '/' + '<?php echo $options['max_description_length'] . ' ' . __('characters used', 'keyword-statistics') ?>';
					if (pdesc.length > <?php echo $options['max_description_length'] ?>)
						status = '<span style="color:red">' + status + '</span>';
					<?php if (strlen ($options['sitewide_description_predecessor']) != 0 || strlen ($options['sitewide_description_successor']) != 0) { ?>
					status = status + '<br/><?php echo __('Preview', 'keyword-statistics') ?>: ' + '<span style="color:#888"><?php echo $options['sitewide_description_predecessor'] ?></span>' + element.value + '<span style="color:#888"><?php echo $options['sitewide_description_successor'] ?></span>';
					<?php } ?>
					break;
			}
			if (document.getElementById (element.id + '_status')) {
				document.getElementById (element.id + '_status').innerHTML = status;
			}
		}
	}
	// initialize previews after page loaded
	var rex = /^ks(title|keywords|description)$/;
	var all = document.getElementsByTagName ('input');
	for (var i = 0; i < all.length; i++)
		if (rex.exec (all[i].id))
			fieldStatus (all[i]);
	all = document.getElementsByTagName ('textarea');
	for (var i = 0; i < all.length; i++)
		if (rex.exec (all[i].id))
			fieldStatus (all[i]);
	/* ]]> */
	</script>
	<input type="hidden" name="" value="<?php wp_create_nonce ('edit-ks_metadata-nonce') ?>" />
	<hr/>
	<div id="keystats"></div>
	<?php
}

function post_keyword_statistics_div () {
	echo '<div class="dbx-b-ox-wrapper">' .
	     '<fieldset id="keywordstat" class="dbx-box">' .
	     '<div class="dbx-h-andle-wrapper"><h3 class="dbx-handle">' . 
	     __('Keyword Statistics', 'keyword-statistics') . "</h3></div>" .   
	     '<div class="dbx-c-ontent-wrapper"><div class="dbx-content">';
	post_keyword_statistics ();
	echo "</div></div></fieldset></div>";
}

function keyword_statistics () {
	if (function_exists ('add_meta_box')) {
		// only works with WP 2.5+
		add_meta_box ('keywordstatbox', __('Keyword Statistics', 'keyword-statistics'), 'post_keyword_statistics', 'post', 'normal', 'core');
		add_meta_box ('keywordstatbox', __('Keyword Statistics', 'keyword-statistics'), 'post_keyword_statistics', 'page', 'normal', 'core');
	} else {
		// older versions
		add_action ('dbx_post_advanced', 'post_keyword_statistics_div');
		add_action ('dbx_page_advanced', 'post_keyword_statistics_div');
	}
}
add_action ('admin_menu', 'keyword_statistics');

// add pages for plugins additional options
function ks_additional_options_menu () {
	// create a new submenu for the different options
	add_menu_page (__('Keyword Statistics', 'keyword-statistics'), __('Keyword Statistics', 'keyword-statistics'), 'administrator', 'ks-additional-menu');
	// sitewide predecessors and successors for title, description and keywords
	add_submenu_page ('ks-additional-menu', __('Sitewide Meta', 'keyword-statistics'), __('Sitewide Meta', 'keyword-statistics'), 'administrator', 'ks-additional-menu', 'ks_additional_options_sitewide_meta');
	// metadata for aggregations
	add_submenu_page ('ks-additional-menu', __('Aggregations Meta', 'keyword-statistics'), __('Aggregations Meta', 'keyword-statistics'), 'administrator', 'ks-additional-menu-aggregation-meta', 'ks_additional_options_aggregation_meta');
	// configuration for serving metadata
	add_submenu_page ('ks-additional-menu', __('Metadata Delivery', 'keyword-statistics'), __('Metadata Delivery', 'keyword-statistics'), 'administrator', 'ks-additional-menu-metadata', 'ks_additional_options_metadata');
	// automatic metadata generation
	add_submenu_page ('ks-additional-menu', __('Metadata Generation', 'keyword-statistics'), __('Metadata Generation', 'keyword-statistics'), 'administrator', 'ks-additional-menu-metadata-generation', 'ks_additional_options_metadata_generation');
	// sitewide default settings for controlling robots
	add_submenu_page ('ks-additional-menu', __('Robots Default', 'keyword-statistics'), __('Robots Default', 'keyword-statistics'), 'administrator', 'ks-additional-menu-robots', 'ks_additional_options_robots');
	// keyword density checker
	add_submenu_page ('ks-additional-menu', __('Keyword Density Checker', 'keyword-statistics'), __('Keyword Density Checker', 'keyword-statistics'), 'administrator', 'ks-additional-menu-keyword-density-checker', 'ks_additional_options_keyword_density_checker');
	// settings for displaying or allowing authors and contributors to change various fields
	add_submenu_page ('ks-additional-menu', __('Authors', 'keyword-statistics'), __('Authors', 'keyword-statistics'), 'administrator', 'ks-additional-menu-authors', 'ks_additional_options_authors');
}
// integrate additionals options menu
add_action('admin_menu', 'ks_additional_options_menu');

// sitewide predecessor and successor for titles, keywords and descriptioms
function ks_additional_options_sitewide_meta () {
	$types = array ('title' => __('titles', 'keyword-statistics'),
			'keywords' => __('keywords', 'keyword-statistics'),
			'description' => __('descriptions', 'keyword-statistics'));
	$options = keyword_statistics_get_plugin_configuration ();
	if (isset ($_POST['ks_update_options']) && $_POST['ks_update_options'] == 1) {
		if (!wp_verify_nonce ($_POST['ks_sitewide_meta_nonce'], 'ks_sitewide_meta_nonce')) {
			$err = 1;
			$errmsg = __('Nonce verification failed:', 'keyword-statistics') . ' ' . __('Error while saving sitewide meta informations!', 'keyword-statistics');
		}
		else {
			// check values
			foreach ($_POST as $key => $value) {
				if (preg_match ('/^ks_(' . implode ('|', array_keys ($types)) . ')_(predecessor|successor)$/', $key, $matches)) {
					$value = stripslashes ($value);
					// fields we can't replace should be removed
					$value = preg_replace ('/%(category_name|tag_name|author_name|archive_date|search_query|pagination)%/', '', $value);
					switch ($matches[1]) {
						case 'title':
							if (strlen ($value) > $options['max_title_length']) {
								$err = 1;
								$errmsg = __('Too many characters used at the title-tag! See configuration at', 'keyword-statistics');
								$errmsg .= ' <a href="' . admin_url ('admin.php?page=ks-additional-menu-metadata-generation') . '" title="">' . __('Metadata Generation', 'keyword-statistics') . '</a>. ';
								$errmsg .= __('There you can define the maximum number of characters allowed for the title-tag.', 'keyword-statistics');
							}
							break;
						case 'keywords':
							// trim whitespaces and not needed commas
							$value = trim (preg_replace ('/[,]+/', ',', $value), ', ');
							if (count (explode (',', $value)) > intval ($options['max_keywords_count']) || strlen ($value) > $options['max_keywords_length']) {
								$err = 1;
								$errmsg = __('Too many characters and/or keywords/phrases used at the keywords tag! At', 'keyword-statistics');
								$errmsg .= ' <a href="' . admin_url ('admin.php?page=ks-additional-menu-metadata-generation') . '" title="">' . __('Metadata Generation', 'keyword-statistics') . '</a> ';
								$errmsg .= __('you can change the configuration options.', 'keyword-statistics');
							}
							break;
						case 'description':
							if (strlen ($value) > $options['max_description_length']) {
								$err = 1;
								$errmsg = __('The description you entered is too long. Have a look at', 'keyword-statistics');
								$errmsg .= ' <a href="' . admin_url ('admin.php?page=ks-additional-menu-metadata-generation') . '" title="">' . __('Metadata Generation', 'keyword-statistics') . '</a> ';
								$errmsg .= __('if you want to change the maximum number of characters for the description fields.', 'keyword-statistics');
							}
							break;
					}
					// update value in options array
					$options['sitewide_' . $matches[1] . '_' . $matches[2]] = htmlspecialchars (strip_tags ($value));
				}
			}
		}
		// show status message
		if (!isset ($err)) { 
			// update options in database if there was no input error
			keyword_statistics_set_plugin_configuration ($options);
			?> <div class="updated"><p><strong><?php _e('Options saved.'); ?></strong></p></div> <?php
		}
		else { ?> <div class="error"><p><strong><?php echo __('Options not saved', 'keyword-statistics') . (isset ($errmsg) ? ' - ' . $errmsg : '!'); ?></strong></p></div> <?php }
	} ?>
	<div class="wrap"><div id="icon-options-general" class="icon32"><br /></div>
		<h2><?php _e('Sitewide Meta', 'keyword-statistics') ?></h2>
		<form name="ks_options" method="post" action="">
			<input type="hidden" name="ks_update_options" value="1" />
			<input type="hidden" name="ks_sitewide_meta_nonce" value="<?php echo wp_create_nonce ('ks_sitewide_meta_nonce') ?>" />
			<table class="form-table">
				<tr><td colspan="2"><span class="description"><?php echo __('At this point you can set any string that will be placed before or after the tags data of any page your blog is serving. You can use the variables <b>%blogname%</b> and <b>%siteurl%</b> only in the fields below. Other variables can not be replaced at any page of your website. Don\'t let these strings get too long because it may will make your titles, keywords or descriptions too long. Remember that they will be added to the particular strings &ndash; title, keywords and description &ndash; defined in pages, posts or the content aggregations meta information. You can get a preview at', 'keyword-statistics') . ' <a href="' . admin_url ('admin.php?page=ks-additional-menu-aggregation-meta') . '" title="">' . __('Aggregations Meta', 'keyword-statistics') . '</a> ' . __('configuation.', 'keyword-statistics') ?></span></td></tr>
				<tr valign="top"><th colspan="2" style="padding-bottom:0;"><h3 style="margin-bottom:0;"><?php _e('Sitewide predecessors / successors', 'keyword-statistics') ?></h3></th></tr>
				<?php foreach ($types as $key => $value) { ?>
				<tr valign="top">
					<th scope="row"><label for="ks_<?php echo $key ?>_predecessor"><?php echo __('Add in front of all', 'keyword-statistics') . ' ' . $value ?>:</label></th>
					<td>
						<input size="60" type="text" name="ks_<?php echo $key ?>_predecessor" id="ks_<?php echo $key ?>_predecessor" value="<?php echo stripslashes ($options['sitewide_' . $key . '_predecessor']) ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="ks_<?php echo $key ?>_successor"><?php echo __('Append after all', 'keyword-statistics') . ' ' . $value ?>:</label></th>
					<td>
						<input size="60" type="text" name="ks_<?php echo $key ?>_successor" id="ks_<?php echo $key ?>_successor" value="<?php echo stripslashes ($options['sitewide_' . $key . '_successor']) ?>" />
					</td>
				</tr>
				<?php } ?>
			</table>
			<p class="submit"><input type="submit" name="Submit" value="<?php _e('Update') ?>" /></p>
		</form>
	</div> <?php
}

// options for defining meta informations of content aggregations
function ks_additional_options_aggregation_meta () {
	// aggregation types
	$aggregations = array ('home' => __('home', 'keyword-statistics'),
			       'category' => __('categories', 'keyword-statistics'),
			       'tag' => __('tags', 'keyword-statistics'),
			       'archive' => __('archives', 'keyword-statistics'),
			       'author' => __('authors', 'keyword-statistics'),
			       'search' => __('search results', 'keyword-statistics'));
	$options = keyword_statistics_get_plugin_configuration ();
	if (isset ($_POST['ks_update_options']) && $_POST['ks_update_options'] == 1) {
		if (!wp_verify_nonce ($_POST['ks_aggregation_meta_nonce'], 'ks_aggregation_meta_nonce')) {
			$err = 1;
			$errmsg = __('Nonce verification failed:', 'keyword-statistics') . ' ' . __('Error while attempting to save aggregations meta information!', 'keyword-statistics');
		}
		else {
			foreach (array_keys ($aggregations) as $key) {
				$options[$key . '_first'] = 0;
				$options[$key . '_index'] = $options[$key . '_follow'] = 'default';
			}
			// check posted options
			foreach ($_POST as $key => $value) {
				if (preg_match ('/^ks_(' . implode ('|', array_keys ($aggregations)) . ')_(title|keywords|description|index|follow|first)$/', $key, $matches)) {
					$value = stripslashes ($value);
					switch ($matches[2]) {
						case 'title':
							$cvalue = $options['sitewide_title_predecessor'] . $value . $options['sitewide_title_successor'];
							if (strlen ($cvalue) > $options['max_title_length']) {
								$err = 1;
								$errmsg = __('At section', 'keyword-statistics') . ' "' . __('Metadata for', 'keyword-statistics') . ' ' . __($aggregations[$matches[1]], 'keyword-statistics') . '": ' . __('The title is containing too many characters!', 'keyword-statistics');
								$errmsg .= ' ' . __('The options for setting the maximum length of this field you\'ll find at', 'keyword-statistics');
								$errmsg .= ' <a href="' . admin_url ('admin.php?page=ks-additional-menu-metadata-generation') . '" title="">' . __('Metadata Generation', 'keyword-statistics') . '</a>.';
							}
							break;
						case 'keywords':
							// trim whitespaces and not needed commas
							$value = trim (preg_replace ('/[,]+/', ',', $value), ', ');
							$cvalue = (strlen ($options['sitewide_keywords_predecessor']) != 0 ? $options['sitewide_keywords_predecessor'] . ',' : '') . $value . (strlen ($options['sitewide_keywords_successor']) != 0 ? ',' . $options['sitewide_keywords_successor'] : '');
							if (count (explode (',', $cvalue)) > intval ($options['max_keywords_count']) || strlen ($cvalue) > $options['max_keywords_length']) {
								$err = 1;
								$errmsg = __('At section', 'keyword-statistics') . ' "' . __('Metadata for', 'keyword-statistics') . ' ' . __($aggregations[$matches[1]], 'keyword-statistics') . '": ' . __('Keywords field is containing too many characters or too many keywords/phrases are included!', 'keyword-statistics');
								$errmsg .= ' ' . __('You can change these settings at', 'keyword-statistics');
								$errmsg .= ' <a href="' . admin_url ('admin.php?page=ks-additional-menu-metadata-generation') . '" title="">' . __('Metadata Generation', 'keyword-statistics') . '</a>.';
							}
							break;
						case 'description':
							$cvalue = $options['sitewide_description_predecessor'] . $value . $options['sitewide_description_successor'];
							if (strlen ($cvalue) > $options['max_description_length']) {
								$err = 1;
								$errmsg = __('At section', 'keyword-statistics') . ' "' . __('Metadata for', 'keyword-statistics') . ' ' . __($aggregations[$matches[1]], 'keyword-statistics') . '": ' . __('The description is containing too many characters!', 'keyword-statistics');
								$errmsg .= ' ' . __('The options for setting the maximum length of this field you\'ll find at', 'keyword-statistics');
								$errmsg .= ' <a href="' . admin_url ('admin.php?page=ks-additional-menu-metadata-generation') . '" title="">' . __('Metadata Generation', 'keyword-statistics') . '</a>.';
							}
							break;
						case 'index':
						case 'follow':
						case 'first':
							break;
					}
					// update value in options array
					$options[$matches[1] . '_' . $matches[2]] = htmlspecialchars (strip_tags ($value));
				}
			}
		}
		// show status message
		if (!isset ($err)) { 
			// update options in database if there was no input error
			keyword_statistics_set_plugin_configuration ($options); ?>
			<div class="updated"><p><strong><?php _e('Options saved.'); ?></strong></p></div> <?php
		}
		else { ?> <div class="error"><p><strong><?php echo __('Options not saved', 'keyword-statistics') . (isset ($errmsg) ? ' - ' . $errmsg : '!'); ?></strong></p></div> <?php }
	} ?>
	<div class="wrap"><div id="icon-options-general" class="icon32"><br /></div>
		<h2><?php _e('Aggregations Meta', 'keyword-statistics') ?></h2>
		<form name="ks_options" method="post" action="">
			<input type="hidden" name="ks_update_options" value="1" />
			<input type="hidden" name="ks_aggregation_meta_nonce" value="<?php echo wp_create_nonce ('ks_aggregation_meta_nonce') ?>" />
			<table class="form-table">
				<tr><td colspan="2"><span class="description"><?php echo __('Here you can set the titles and meta informations for the different content aggregations. If you leave blank the fields for title or description, these tags will be filled with the informations generated automatically from the listed posts. The keywords you set here will be filled up automatically with the ones from the listed posts - up to the maximum number of keywords defined at', 'keyword-statistics') . ' <a href="' . admin_url ('admin.php?page=ks-additional-menu-metadata-generation') . '" title="">' . __('Metadata Generation', 'keyword-statistics') . '</a>. ' . __('You can use the variables listed in the table below. Each of the textfields comes with a preview. There you can see the values of the different tags with the predecessors and successors defined at', 'keyword-statistics') . ' <a href="' . admin_url ('admin.php?page=ks-additional-menu') . '" title="">' . __('Sitewide Meta', 'keyword-statistics') . '</a>.' ?></span></td></tr>
			<?php foreach ($aggregations as $key => $value) { ?>
				<tr valign="top"><th colspan="2" style="padding-bottom:0;"><h3 style="margin-bottom:0;"><?php echo __('Metadata for', 'keyword-statistics') . ' ' . $value ?></h3></th></tr>
				<tr valign="top">
					<th scope="row"><label for="ks_<?php echo $key ?>_title"><?php _e('Title', 'keyword-statistics') ?>:</label></th>
					<td>
						<input onkeyup="setPreview(this)" size="60" type="text" name="ks_<?php echo $key ?>_title" id="ks_<?php echo $key ?>_title" value="<?php echo stripslashes ($options[$key . '_title']) ?>" /><br/>
						<?php _e('Preview', 'keyword-statistics') ?>: <span id="ks_<?php echo $key ?>_title_preview"></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="ks_<?php echo $key ?>_keywords"><?php _e('Keywords', 'keyword-statistics') ?>:</label></th>
					<td>
						<input onkeyup="setPreview(this)" size="60" type="text" name="ks_<?php echo $key ?>_keywords" id="ks_<?php echo $key ?>_keywords" value="<?php echo stripslashes ($options[$key . '_keywords']) ?>" /><br/>
						<?php _e('Preview', 'keyword-statistics') ?>: <span id="ks_<?php echo $key ?>_keywords_preview"></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="ks_<?php echo $key ?>_description"><?php _e('Description', 'keyword-statistics') ?>:</label></th>
					<td>
						<textarea onkeyup="setPreview(this)" cols="60" rows="3" name="ks_<?php echo $key ?>_description" id="ks_<?php echo $key ?>_description"><?php echo stripslashes ($options[$key . '_description']) ?></textarea><br/>
						<?php _e('Preview', 'keyword-statistics') ?>: <span id="ks_<?php echo $key ?>_description_preview"></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="ks_<?php echo $key ?>_index"><?php _e('Robots', 'keyword-statistics') ?>:</label></th>
					<td>
						<input type="radio" name="ks_<?php echo $key ?>_index" id="ks_<?php echo $key ?>_index" value="default" <?php echo $options[$key . '_index'] == 'default' ? 'checked="checked"' : '' ?> /> <?php _e('default', 'keyword-statistics') ?>
						<input type="radio" name="ks_<?php echo $key ?>_index" id="ks_<?php echo $key ?>_index" value="index" <?php echo $options[$key . '_index'] == 'index' ? 'checked="checked"' : '' ?> /> <?php _e('index', 'keyword-statistics') ?>
						<input type="radio" name="ks_<?php echo $key ?>_index" id="ks_<?php echo $key ?>_index" value="noindex" <?php echo $options[$key . '_index'] == 'noindex' ? 'checked="checked"' : '' ?> /> <?php _e('noindex', 'keyword-statistics') ?><br/>
						<input type="radio" name="ks_<?php echo $key ?>_follow" id="ks_<?php echo $key ?>_follow" value="default" <?php echo $options[$key . '_follow'] == 'default' ? 'checked="checked"' : '' ?> /> <?php _e('default', 'keyword-statistics') ?>
						<input type="radio" name="ks_<?php echo $key ?>_follow" id="ks_<?php echo $key ?>_follow" value="follow" <?php echo $options[$key . '_follow'] == 'follow' ? 'checked="checked"' : '' ?> /> <?php _e('follow', 'keyword-statistics') ?>
						<input type="radio" name="ks_<?php echo $key ?>_follow" id="ks_<?php echo $key ?>_follow" value="nofollow" <?php echo $options[$key . '_follow'] == 'nofollow' ? 'checked="checked"' : '' ?> /> <?php _e('nofollow', 'keyword-statistics') ?><br/>
						<?php if ($key == 'home') { ?>
						<span class="description"><?php echo __('If you set this to default, the settings at', 'keyword-statistics') . ' <a href="' . admin_url ('admin.php?page=ks-additional-menu-robots') . '" title="">' . __('Robots Default', 'keyword-statistics') . '</a> ' . __('will be used for this kind of content aggregation.', 'keyword-statistics') ?></span>
						<?php } ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="ks_<?php echo $key ?>_index"><?php _e('Only for the first page', 'keyword-statistics') ?>:</label></th>
					<td>
						<input type="checkbox" name="ks_<?php echo $key ?>_first" id="ks_<?php echo $key ?>_first" value="1" <?php echo $options[$key . '_first'] == 1 ? 'checked="checked"' : '' ?> /><br/>
						<?php if ($key == 'home') { ?>
						<span class="description"><?php echo __('If this is activated, the options above will only be served at the first page of the particular aggregation. The other pages of the aggregation are handled automatically (see settings at', 'keyword-statistics') . ' <a href="' . admin_url ('admin.php?page=ks-additional-menu-metadata-generation') . '" title="">' . __('Metadata Generation', 'keyword-statistics') . '</a>).' ?></span>
						<?php } ?>
					</td>
				</tr>
			<?php } ?>
			</table>
			<p class="submit"><input type="submit" name="Submit" value="<?php _e('Update') ?>" /></p>
		</form>
		<p>
			<span style="font-weight:bold"><?php _e('Variables you can use and their values:', 'keyword-statistics') ?></span>
			<style type="text/css">
			/* <![CDATA[ */
			table.vars { border:1px solid black }
			table.vars td, table.vars th { font-size:0.8em }
			table.vars td { border-top:1px solid black }
			/* ]]> */
			</style>
			<table class="vars">
				<thead>
					<tr align="left">
						<th><?php _e('Variable', 'keyword-statistics') ?></th>
						<th><?php _e('Value (example)', 'keyword-statistics') ?></th>
						<th><?php _e('Description', 'keyword-statistics') ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>%blogname%</td>
						<td><?php $var_blogname = ks_replace_variables ('%blogname%'); echo $var_blogname ?></td>
						<td><?php _e('The blogs title as defined at the standard configuration. You can use this variable in <b>any</b> of the fields.', 'keyword-statistics') ?></td>
					</tr>
					<tr>
						<td>%siteurl%</td>
						<td><?php $var_siteurl = ks_replace_variables ('%siteurl%'); echo $var_siteurl ?></td>
						<td><?php _e('URL of your blog. Also usable at <b>any</b> of the fields above.', 'keyword-statistics') ?></td>
					</tr>
					<tr>
						<td>%category_name%</td>
						<td><?php $categories = get_categories (array ('orderby' => 'count', 'number' => 1)); if (is_array ($categories) && count ($categories) > 0) $var_category = ks_replace_variables ('%category_name%', array ('category_name' => $categories[key ($categories)]->name)); else $var_category = __('No categories assigned!', 'keyword-statistics'); echo $var_category ?></td>
						<td><?php _e('Name of the currently listed category. Only replaced in <b>category</b> meta fields.', 'keyword-statistics') ?></td>
					</tr>
					<tr>
						<td>%tag_name%</td>
						<td><?php $tags = get_tags (array ('orderby' => 'count', 'number' => 1)); if (is_array ($tags) && count ($tags) > 0) $var_tag = ks_replace_variables ('%tag_name%', array ('tag_name' => $tags[key ($tags)]->name)); else $var_tag = __('No tags assigned!', 'keyword-statistics'); echo $var_tag ?></td>
						<td><?php _e('Same as with category name but only on <b>tag</b> views.', 'keyword-statistics') ?></td>
					</tr>
					<tr>
						<td>%author_name%</td>
						<td><?php $var_author = ks_replace_variables ('%author_name%', array ('author_name' => 'Will Writer')); echo $var_author ?></td>
						<td><?php _e('Authors name when opening author aggregations. Only availiable at <b>author</b> meta definitions.', 'keyword-statistics') ?></td>
					</tr>
					<tr>
						<td>%archive_date%</td>
						<td><?php $var_archive = ks_replace_variables ('%archive_date%', array ('m' => '20100226')); echo $var_archive ?></td>
						<td><?php _e('This variable is only availiable at <b>archive</b> meta definitions and will be replaced only on archive-views of your blog.', 'keyword-statistics') ?></td>
					</tr>
					<tr>
						<td>%search_query%</td>
						<td><?php $var_search_query = ks_replace_variables ('%search_query%', array ('s' => __('how to use variables', 'keyword-statistics'))); echo $var_search_query ?></td>
						<td><?php _e('Will be replaced with the search query on search result pages. Should only be used on meta configuration for <b>search results</b>.', 'keyword-statistics') ?></td>
					</tr>
					<tr>
						<td>%pagination%</td>
						<td><?php $var_pagination = ks_replace_variables ('%pagination%', array ('paged' => 3)); echo $var_pagination ?></td>
						<td><?php _e('Pagination containing the page actually displayed. On the first page of an aggregation this will be deleted. You can use it in <b>any</b> field above.', 'keyword-statistics') ?></td>
					</tr>
				</tbody>
			</table>
		</p>
		<script type="text/javascript">
		/* <![CDATA[ */
		// let's replace variables to generate a preview of the
		function setPreview (element) {
			var content = element.value;
			var rex = /^ks_([a-z]+)_([a-z]+)$/;
			if (rex.exec (element.id)) {
				var type = RegExp.$2, section = RegExp.$1;
				// global values
				content = content.replace (/%blogname%/g, "<?php echo $var_blogname ?>");
				content = content.replace (/%siteurl%/g, "<?php echo $var_siteurl ?>");
				content = content.replace (/%pagination%/g, "<?php echo $var_pagination ?>");
				// values only available in certain sections
				switch (section) {
					case 'category':
						content = content.replace (/%category_name%/g, "<?php echo $var_category ?>");
						break;
					case 'tag':
						content = content.replace (/%tag_name%/g, "<?php echo $var_tag ?>");
						break;
					case 'archive':
						content = content.replace (/%archive_date%/g, "<?php echo $var_archive ?>");
						break;
					case 'author':
						content = content.replace (/%author_name%/g, "<?php echo $var_author ?>");
						break;
					case 'search':
						content = content.replace (/%search_query%/g, "<?php echo $var_search_query ?>");
						break;
				}
			}
			// mark all not replacable variables
			var content_raw = content.replace (/(%[^%]+%)/g, "")
			content = content.replace (/(%[^%]+%)/g, "<strike>$1</strike>");
			// append sitewide predecessor and successor
			var pred = succ = '';
			switch (type) {
				case 'title':
					pred = '<?php echo ks_replace_variables ($options['sitewide_title_predecessor']) ?>';
					succ = '<?php echo ks_replace_variables ($options['sitewide_title_successor']) ?>';
					break;
				case 'keywords':
					pred = '<?php echo $options['sitewide_keywords_predecessor'] ?>';
					succ = '<?php echo $options['sitewide_keywords_successor'] ?>';
					if (pred.length != 0 && (content.length != 0 || succ.length != 0))
						pred = pred + ',';
					if (content.length != 0 && succ.length != 0)
						succ = ',' + succ;
					break;
				case 'description':
					pred = '<?php echo ks_replace_variables ($options['sitewide_description_predecessor']) ?>';
					succ = '<?php echo ks_replace_variables ($options['sitewide_description_successor']) ?>';
					break;
			}
			content_raw = pred + content_raw + succ;
			content = '<span style="color:#888">' + pred + '</span>' + content +
				  '<span style="color:#888">' + succ + '</span>';
			if (document.getElementById (element.id + '_preview')) {
				switch (type) {
					case 'title':
						var len = ' (' + content_raw.length + '/<?php echo $options['max_title_length'] ?> <?php echo _e('characters used', 'keyword-statistics') ?>)';
						content = content + (content_raw.length > <?php echo $options['max_title_length'] ?> ? '<span style="color:red">' + len + '</span>' : len);
						break;
					case 'keywords':
						var keys = content_raw.split (',');
						for (var i = 0; i < keys.length; i++)
							if (keys[i].replace (/\s/g, '').length == 0)
								keys.splice (i, 1);
						var keycount = ' (' + keys.length + '/<?php echo $options['max_keywords_count'] ?> <?php echo _e('keywords', 'keyword-statistics') ?>' + ' - ' + content_raw.length + '/<?php echo $options['max_keywords_length'] ?> <?php echo _e('characters used', 'keyword-statistics') ?>)';
						content = content + (keys.length > <?php echo $options['max_keywords_count'] ?> || content_raw.length > <?php echo $options['max_keywords_length'] ?> ? '<span style="color:red">' + keycount + '</span>' : keycount);
						break;
					case 'description':
						var len = ' (' + content_raw.length + '/<?php echo $options['max_description_length'] ?> <?php echo _e('characters used', 'keyword-statistics') ?>)';
						content = content + (content_raw.length < <?php echo $options['min_description_length'] ?> || content_raw.length > <?php echo $options['max_description_length'] ?> ? '<span style="color:red">' + len + '</span>' : len);
						break;
				}
				document.getElementById (element.id + '_preview').innerHTML = content;
			}
		}
		// initialize previews after page loaded
		var rex = /^ks_([a-z]+)_([a-z]+)$/;
		var all = document.getElementsByTagName ('input');
		for (var i = 0; i < all.length; i++)
			if (rex.exec (all[i].id))
				setPreview (all[i]);
		all = document.getElementsByTagName ('textarea');
		for (var i = 0; i < all.length; i++)
			if (rex.exec (all[i].id))
				setPreview (all[i]);
		/* ]]> */
		</script>
	</div> <?php
}

// Authors options
function ks_additional_options_authors () {
	$options = keyword_statistics_get_plugin_configuration ();
	if (isset ($_POST['ks_update_options']) && $_POST['ks_update_options'] == 1) {
		if (!wp_verify_nonce ($_POST['ks_author_options_nonce'], 'ks_author_options_nonce')) {
			$err = 1;
			$errmsg = __('Nonce verification failed:', 'keyword-statistics') . ' ' . __('Error while saving plugin configuration for authors!', 'keyword-statistics');
		}
		else {
			$options['authors_can_change_content_language'] = $options['authors_can_disable_stopword_filter'] = $options['authors_can_set_individual_title'] = $options['authors_can_edit_keywords'] = $options['authors_can_edit_description'] = $options['authors_can_control_robots'] = $options['hide_fields_authors_cant_change'] = $options['hide_fields_authors_cant_change_from_admin'] = 0;
			foreach ($_POST as $key => $value) {
				switch ($key) {
					case 'ks_authors_can_change_content_language':
						$options['authors_can_change_content_language'] = 1;
						break;
					case 'ks_authors_can_disable_stopword_filter':
						$options['authors_can_disable_stopword_filter'] = 1;
						break;
					case 'ks_authors_can_set_individual_title':
						$options['authors_can_set_individual_title'] = 1;
						break;
					case 'ks_authors_can_edit_keywords':
						$options['authors_can_edit_keywords'] = 1;
						break;
					case 'ks_authors_can_edit_description':
						$options['authors_can_edit_description'] = 1;
						break;
					case 'ks_authors_can_control_robots':
						$options['authors_can_control_robots'] = 1;
						break;
					case 'ks_hide_fields_authors_cant_change':
						$options['hide_fields_authors_cant_change'] = 1;
						break;
					case 'ks_hide_fields_authors_cant_change_from_admin':
						$options['hide_fields_authors_cant_change_from_admin'] = 1;
						break;
				}
			}
		}
		// show status message
		if (!isset ($err)) { 
			// update options in database if there was no input error
			keyword_statistics_set_plugin_configuration ($options);
			?> <div class="updated"><p><strong><?php _e('Options saved.'); ?></strong></p></div> <?php
		}
		else { ?> <div class="error"><p><strong><?php echo __('Options not saved', 'keyword-statistics') . (isset ($errmsg) ? ' - ' . $errmsg : '!'); ?></strong></p></div> <?php }
	} ?>
	<div class="wrap"><div id="icon-options-general" class="icon32"><br /></div>
		<h2><?php _e('Authors', 'keyword-statistics') ?></h2>
		<form name="ks_options" method="post" action="">
			<input type="hidden" name="ks_update_options" value="1" />
			<input type="hidden" name="ks_author_options_nonce" value="<?php echo wp_create_nonce ('ks_author_options_nonce') ?>" />
			<table class="form-table">
				<tr><td colspan="2"><span class="description"><?php _e('At this point you can allow or disallow authors (and distributors) to change fields defining the meta information for a particular page or post. Moreover you can determine if fields they can\'t change should be displayed for authors.', 'keyword-statistics') ?></span></td></tr>
				<tr valign="top">
					<th scope="row"><?php _e('Authors can', 'keyword-statistics') ?></th>
					<td>
						<input type="checkbox" name="ks_authors_can_change_content_language" value="1" <?php echo $options['authors_can_change_content_language'] == 1 ? 'checked="checked"' : '' ?> /> <?php _e('change the content language', 'keyword-statistics') ?><br/>
						<input type="checkbox" name="ks_authors_can_disable_stopword_filter" value="1" <?php echo $options['authors_can_disable_stopword_filter'] == 1 ? 'checked="checked"' : '' ?> /> <?php _e('switch status of the stopwords-filter', 'keyword-statistics') ?><br/>
						<input type="checkbox" name="ks_authors_can_set_individual_title" value="1" <?php echo $options['authors_can_set_individual_title'] == 1 ? 'checked="checked"' : '' ?> /> <?php _e('set individual title-tag', 'keyword-statistics') ?><br/>
						<input type="checkbox" name="ks_authors_can_edit_keywords" value="1" <?php echo $options['authors_can_edit_keywords'] == 1 ? 'checked="checked"' : '' ?> /> <?php _e('edit keywords', 'keyword-statistics') ?><br/>
						<input type="checkbox" name="ks_authors_can_edit_description" value="1" <?php echo $options['authors_can_edit_description'] == 1 ? 'checked="checked"' : '' ?> /> <?php _e('edit description', 'keyword-statistics') ?><br/>
						<input type="checkbox" name="ks_authors_can_control_robots" value="1" <?php echo $options['authors_can_control_robots'] == 1 ? 'checked="checked"' : '' ?> /> <?php _e('control robots', 'keyword-statistics') ?><br/>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Hide fields authors can\'t change', 'keyword-statistics') ?></th>
					<td>
						<input type="checkbox" name="ks_hide_fields_authors_cant_change" value="1" <?php echo $options['hide_fields_authors_cant_change'] == 1 ? 'checked="checked"' : '' ?> /><br />
						<span class="description"><?php _e('Fields authors can\'t change may confuse them and is wasting space on screen. Activate this option to hide these fields.', 'keyword-statistics') ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Hide fields authors can\'t change from admin', 'keyword-statistics') ?></th>
					<td>
						<input type="checkbox" name="ks_hide_fields_authors_cant_change_from_admin" value="1" <?php echo $options['hide_fields_authors_cant_change_from_admin'] == 1 ? 'checked="checked"' : '' ?> /><br />
						<span class="description"><?php _e('Hide the fields for admins too. This will save space in page-/post-edit if the statistics tool is used for checking the keyword densities only.', 'keyword-statistics') ?></span>
					</td>
				</tr>
			</table>
			<p class="submit"><input type="submit" name="Submit" value="<?php _e('Update') ?>" /></p>
		</form>
	</div> <?php
}

// robots default settings
function ks_additional_options_robots () {
	$options = keyword_statistics_get_plugin_configuration ();
	if (isset ($_POST['ks_update_options']) && $_POST['ks_update_options'] == 1) {
		if (!wp_verify_nonce ($_POST['ks_robots_nonce'], 'ks_robots_nonce')) {
			$err = 1;
			$errmsg = __('Nonce verification failed:', 'keyword-statistics') . ' ' . __('Error while saving configuration for controlling robot!', 'keyword-statistics');
		}
		else {
			$options['feeds_noindex'] = $options['noodp'] = $options['noydir'] = $options['noarchive'] = $options['index_aggregated'] = $options['follow_aggregated'] = 0;
			foreach ($_POST as $key => $value) {
				switch ($key) {
					case 'ks_noodp':
						$options['noodp'] = 1;
						break;
					case 'ks_noydir':
						$options['noydir'] = 1;
						break;
					case 'ks_noarchive':
						$options['noarchive'] = 1;
						break;
					case 'ks_index_aggregated':
						$options['index_aggregated'] = 1;
						break;
					case 'ks_follow_aggregated':
						$options['follow_aggregated'] = 1;
						break;
					case 'ks_feeds_noindex':
						$options['feeds_noindex'] = 1;
						break;
				}
			}
		}
		// show status message
		if (!isset ($err)) { 
			// update options in database if there was no input error
			keyword_statistics_set_plugin_configuration ($options);
			?> <div class="updated"><p><strong><?php _e('Options saved.'); ?></strong></p></div> <?php
		}
		else { ?> <div class="error"><p><strong><?php echo __('Options not saved', 'keyword-statistics') . (isset ($errmsg) ? ' - ' . $errmsg : '!'); ?></strong></p></div> <?php }
	} ?>
	<div class="wrap"><div id="icon-options-general" class="icon32"><br /></div>
		<h2><?php _e('Robots Default Settings', 'keyword-statistics') ?></h2>
		<form name="ks_options" method="post" action="">
			<input type="hidden" name="ks_update_options" value="1" />
			<input type="hidden" name="ks_robots_nonce" value="<?php echo wp_create_nonce ('ks_robots_nonce') ?>" />
			<table class="form-table">
				<tr><td colspan="2"><span class="description"><?php echo __('Here you can configure the default settings - index and follow - for the robots meta tag on content aggregations. These will be served for all aggregations that are not set to "default" at', 'keyword-statistics') . ' <a href="' . admin_url ('admin.php?page=ks-additional-menu-aggregation-meta') . '" title="">' . __('Aggregations Meta', 'keyword-statistics') . '</a> ' . __('configuation. You also can configure the settings for ODP, Yahoo! Directory and archiving (caching of your pages at search engines and archive.org) - ydir, odp and archive. These will be served with any page of your blog.', 'keyword-statistics') ?></span></td></tr>
				<tr valign="top"><th colspan="2" style="padding-bottom:0;"><h3 style="margin-bottom:0;"><?php _e('Settings for Content Aggregations', 'keyword-statistics') ?></h3></th></tr>
				<tr valign="top">
					<th scope="row"><?php _e('Robots', 'keyword-statistics') ?></th>
					<td>
						<input type="checkbox" name="ks_index_aggregated" value="1" <?php echo $options['index_aggregated'] == 1 ? 'checked="checked"' : '' ?> /> <?php _e('allow robots to index aggregated content', 'keyword-statistics') ?><br/>
						<input type="checkbox" name="ks_follow_aggregated" value="1" <?php echo $options['follow_aggregated'] == 1 ? 'checked="checked"' : '' ?> /> <?php _e('allow robots to follow links in aggregated pages', 'keyword-statistics') ?><br/>
						<span class="description"><?php _e('This options can be used to manipulate the robots meta tag in all kinds of content-aggregations (category-, tag-, archive-, author- and post-lists).', 'keyword-statistics') ?></span>
					</td>
				</tr>
				<tr valign="top"><th colspan="2" style="padding-bottom:0;"><h3 style="margin-bottom:0;"><?php _e('Sitewide settings for ODP, Yahoo! Directory and archiving', 'keyword-statistics') ?></h3></th></tr>
				<tr valign="top">
					<th scope="row"><?php _e('Robots', 'keyword-statistics') ?></th>
					<td>
						<input type="checkbox" name="ks_noodp" value="1" <?php echo $options['noodp'] == 1 ? 'checked="checked"' : '' ?> /> <?php _e('don\'t use information from ODP', 'keyword-statistics') ?><br/>
						<input type="checkbox" name="ks_noydir" value="1" <?php echo $options['noydir'] == 1 ? 'checked="checked"' : '' ?> /> <?php _e('don\'t use information from Yahoo! Directory', 'keyword-statistics') ?><br/>
						<input type="checkbox" name="ks_noarchive" value="1" <?php echo $options['noarchive'] == 1 ? 'checked="checked"' : '' ?> /> <?php _e('prevent archiving on archive.org and search engines', 'keyword-statistics') ?><br/>
						<span class="description"><?php _e('These settings have infuence on sitewide robots meta data (if checked noodp, noydir or noarchive will be used).', 'keyword-statistics') ?></span>
					</td>
				</tr>
				<tr valign="top"><th colspan="2" style="padding-bottom:0;"><h3 style="margin-bottom:0;"><?php _e('Feeds', 'keyword-statistics') ?></h3></th></tr>
				<tr valign="top">
					<th scope="row"><?php _e('Robots', 'keyword-statistics') ?></th>
					<td>
						<input type="checkbox" name="ks_feeds_noindex" value="1" <?php echo $options['feeds_noindex'] == 1 ? 'checked="checked"' : '' ?> /> <?php _e('disallow robots to index the blog\'s feeds', 'keyword-statistics') ?><br/>
						<span class="description"><?php _e('This option can be used for controlling the indexation of the blog\'s feeds. If checked the posts and comments feeds will be set to noindex at the header of all feed files delivered by the bog.', 'keyword-statistics') ?></span>
					</td>
				</tr>
			</table>
			<p class="submit"><input type="submit" name="Submit" value="<?php _e('Update') ?>" /></p>
		</form>
	</div> <?php
}

// metadata delivery settings
function ks_additional_options_metadata () {
	$options = keyword_statistics_get_plugin_configuration ();
	if (isset ($_POST['ks_update_options']) && $_POST['ks_update_options'] == 1) {
		if (!wp_verify_nonce ($_POST['ks_serve_meta_nonce'], 'ks_serve_meta_nonce')) {
			$err = 1;
			$errmsg = __('Nonce verification failed:', 'keyword-statistics') . ' ' . __('Error while saving options for serving meta informations!', 'keyword-statistics');
		}
		else {
			$options['serve_title'] = $options['serve_meta_robots'] = $options['serve_meta_keywords'] = $options['serve_meta_description'] = $options['serve_meta_canonical'] = $options['dont_serve_metadata'] = $options['testmode'] = 0;
			foreach ($_POST as $key => $value) {
				switch ($key) {
					case 'ks_serve_title':
						$options['serve_title'] = 1;
						break;
					case 'ks_serve_meta_robots':
						$options['serve_meta_robots'] = 1;
						break;
					case 'ks_serve_meta_keywords':
						$options['serve_meta_keywords'] = 1;
						break;
					case 'ks_serve_meta_description':
						$options['serve_meta_description'] = 1;
						break;
					case 'ks_serve_meta_canonical':
						$options['serve_meta_canonical'] = 1;
						break;
					case 'ks_dont_serve_metadata':
						$options['dont_serve_metadata'] = 1;
						break;
					case 'ks_testmode':
						$options['testmode'] = 1;
						break;
				}
			}
		}
		// show status message
		if (!isset ($err)) { 
			// update options in database if there was no input error
			keyword_statistics_set_plugin_configuration ($options);
			?> <div class="updated"><p><strong><?php _e('Options saved.'); ?></strong></p></div> <?php
		}
		else { ?> <div class="error"><p><strong><?php echo __('Options not saved', 'keyword-statistics') . (isset ($errmsg) ? ' - ' . $errmsg : '!'); ?></strong></p></div> <?php }
	} ?>
	<div class="wrap"><div id="icon-options-general" class="icon32"><br /></div>
		<h2><?php _e('Metadata Delivery', 'keyword-statistics') ?></h2>
		<form name="ks_options" method="post" action="">
			<input type="hidden" name="ks_update_options" value="1" />
			<input type="hidden" name="ks_serve_meta_nonce" value="<?php echo wp_create_nonce ('ks_serve_meta_nonce') ?>" />
			<table class="form-table">
				<tr><td colspan="2"><span class="description"><?php _e('The settings below are used to configure which meta informations (tag) should be served by your blog. You can individually switch off or on particular tags or disallow the plugin to serve any meta information - e.g. if you use other plugins for serving meta informations instead and don\'t want to generate multiple tags of one type.', 'keyword-statistics') ?></span></td></tr>
				<tr valign="top"><th colspan="2" style="padding-bottom:0;"><h3 style="margin-bottom:0;"><?php _e('Meta tag configuration', 'keyword-statistics') ?></h3></th></tr>
				<tr valign="top">
					<th scope="row"><?php _e('Serve title tag', 'keyword-statistics') ?></th>
					<td>
						<input type="checkbox" name="ks_serve_title" value="1" <?php echo $options['serve_title'] == 1 ? 'checked="checked"' : '' ?> /><br/>
						<span class="description"><?php _e('If you don\'t want to let the plugin serve the page title-tag, you can deactivate this option. In this case the title generated by WordPress or another plugin will stay in the HTML code of each page.', 'keyword-statistics') ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Serve meta informations for', 'keyword-statistics') ?></th>
					<td>
						<input type="checkbox" name="ks_serve_meta_robots" value="1" <?php echo $options['serve_meta_robots'] == 1 ? 'checked="checked"' : '' ?> /> <?php _e('robots', 'keyword-statistics') ?><br/>
						<span class="description"><?php _e('If you want to use a robots.txt file to control robots you can uncheck this option. After that no robots meta will be embedded into the pages of your blog.', 'keyword-statistics') ?></span><br/>
						<input type="checkbox" name="ks_serve_meta_keywords" value="1" <?php echo $options['serve_meta_keywords'] == 1 ? 'checked="checked"' : '' ?> /> <?php _e('keywords', 'keyword-statistics') ?><br/>
						<span class="description"><?php _e('Switch this off if you don\'t want your blog to serve the keywords generated automatic by the plugins keyword statistics or your custom set of keywords set on each post or page. The keywords will also not be added to the HTML meta section on all kinds of content aggregations.', 'keyword-statistics') ?></span><br/>
						<input type="checkbox" name="ks_serve_meta_description" value="1" <?php echo $options['serve_meta_description'] == 1 ? 'checked="checked"' : '' ?> /> <?php _e('description', 'keyword-statistics') ?><br/>
						<span class="description"><?php _e('Same as with the keywords option described before.', 'keyword-statistics') ?></span><br/>
						<input type="checkbox" name="ks_serve_meta_canonical" value="1" <?php echo $options['serve_meta_canonical'] == 1 ? 'checked="checked"' : '' ?> /> <?php _e('canonical', 'keyword-statistics') ?><br/>
						<span class="description"><?php _e('Don\' let the plugin serve the canonical urls at any page served by the blog - including the various content aggregations - if this is off. In this case the canonical generation of WordPress 2.9+ will be reactivated. In older WordPress versions no canonical will be generated if you are not running another plugin doing this.', 'keyword-statistics') ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Don\'t serve any meta information', 'keyword-statistics') ?></th>
					<td>
						<input type="checkbox" name="ks_dont_serve_metadata" value="1" <?php echo $options['dont_serve_metadata'] == 1 ? 'checked="checked"' : '' ?> /><br/>
						<span class="description"><?php _e('This option overwrites the ones above so if you activate it no metadata will be served. If you are using other plugins for adding meta informations you can check this option. This way no metadata will be inserted at any page of your blog. The plugin can still be used to check keyword densities.', 'keyword-statistics') ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Activate testmode', 'keyword-statistics') ?></th>
					<td>
						<input type="checkbox" name="ks_testmode" value="1" <?php echo $options['testmode'] == 1 ? 'checked="checked"' : '' ?> /><br/>
						<span class="description"><?php _e('If activated the meta informations are served within a HTML comment. Search engines ignore these informations but while testing the plugins output you can see what will be served if you are not in testmode. This way you can compare the plugins metadata with the ones integrated by other plugins doing the same or similar work.', 'keyword-statistics') ?></span>
					</td>
				</tr>
			</table>
			<p class="submit"><input type="submit" name="Submit" value="<?php _e('Update') ?>" /></p>
		</form>
	</div> <?php
}

// settings for keyword density checker
function ks_additional_options_keyword_density_checker () {
	$options = keyword_statistics_get_plugin_configuration ();
	if (isset ($_POST['ks_update_options']) && $_POST['ks_update_options'] == 1) {
		if (!wp_verify_nonce ($_POST['ks_density_checker_nonce'], 'ks_density_checker_nonce')) {
			$err = 1;
			$errmsg = __('Nonce verification failed:', 'keyword-statistics') . ' ' . __('Error while saving options for the keyword density checker!', 'keyword-statistics');
		}
		else {
			$options['set_language_meta'] = $options['filter_stopwords'] = $options['automatic_update'] = $options['2word_phrases'] = $options['3word_phrases'] = 0;
			foreach ($_POST as $key => $value) {
				switch ($key) {
					case 'ks_set_language_meta':
						$options['set_language_meta'] = 1;
						break;
					case 'ks_filter_stopwords':
						$options['filter_stopwords'] = 1;
						break;
					case 'ks_automatic_update':
						$options['automatic_update'] = 1;
						break;
					case 'ks_2word_phrases':
						$options['2word_phrases'] = 1;
						break;
					case 'ks_3word_phrases':
						$options['3word_phrases'] = 1;
						break;
					case 'ks_max_list_items':
					case 'ks_update_interval':
					case 'ks_min_words':
						if (preg_match ('/[0-9]+/', $value))
							$options[substr ($key, 3)] = $value;
						else {
							$err = 1;
							//$errmsg = __('', 'keyword-statistics');
						}
						break;
					case 'ks_default_language':
						if (preg_match ('/[a-z-]+/', $key))
							$options['default_language'] = $value;
						else {
							$err = 1;
							//$errmsg = __('', 'keyword-statistics');
						}
						break;
				}
			}
		}
		// show status message
		if (!isset ($err)) { 
			// update options in database if there was no input error
			keyword_statistics_set_plugin_configuration ($options);
			?> <div class="updated"><p><strong><?php _e('Options saved.'); ?></strong></p></div> <?php
		}
		else { ?> <div class="error"><p><strong><?php echo __('Options not saved', 'keyword-statistics') . (isset ($errmsg) ? ' - ' . $errmsg : '!'); ?></strong></p></div> <?php }
	} ?>
	<div class="wrap"><div id="icon-options-general" class="icon32"><br /></div>
		<h2><?php _e('Keyword Density Checker', 'keyword-statistics') ?></h2>
		<form name="ks_options" method="post" action="">
			<input type="hidden" name="ks_update_options" value="1" />
			<input type="hidden" name="ks_density_checker_nonce" value="<?php echo wp_create_nonce ('ks_density_checker_nonce') ?>" />
			<table class="form-table">
				<tr><td colspan="2"><span class="description"><?php echo __('With these options you can configure the keyword density checker. You can set a stopwords filter and in which iterval the statistics will be generated. These settings are affecting the statistics shown at edit page/post only. The settings for the automatic generation of meta informations can be found at ', 'keyword-statistics') . '<a href="' . admin_url ('admin.php?page=ks-additional-menu-metadata-generation') . '" title="">' . __('Metadata Generation', 'keyword-statistics') . '</a>.' ?></span></td></tr>
				<tr valign="top">
					<th scope="row"><?php _e('Default language', 'keyword-statistics') ?></th>
					<td>
						<select name="ks_default_language">
							<option value="en" <?php echo $options['default_language'] == 'en' ? ' selected="selected"' : '' ?>>en</option>
							<option value="bg" <?php echo $options['default_language'] == 'bg' ? ' selected="selected"' : '' ?>>bg</option>
							<option value="cs-cz" <?php echo $options['default_language'] == 'cs-cz' ? ' selected="selected"' : '' ?>>cs-cz</option>
							<option value="da" <?php echo $options['default_language'] == 'da' ? ' selected="selected"' : '' ?>>da</option>
							<option value="de" <?php echo $options['default_language'] == 'de' ? ' selected="selected"' : '' ?>>de</option>
							<option value="es" <?php echo $options['default_language'] == 'es' ? ' selected="selected"' : '' ?>>es</option>
							<option value="fr" <?php echo $options['default_language'] == 'fr' ? ' selected="selected"' : '' ?>>fr</option>
							<option value="hu" <?php echo $options['default_language'] == 'hu' ? ' selected="selected"' : '' ?>>hu</option>
							<option value="nl" <?php echo $options['default_language'] == 'nl' ? ' selected="selected"' : '' ?>>nl</option>
							<option value="pl" <?php echo $options['default_language'] == 'pl' ? ' selected="selected"' : '' ?>>pl</option>
							<option value="pt-br" <?php echo $options['default_language'] == 'pt-br' ? ' selected="selected"' : '' ?>>pt-br</option>
							<option value="sk" <?php echo $options['default_language'] == 'sk' ? ' selected="selected"' : '' ?>>sk</option>
							<option value="tr" <?php echo $options['default_language'] == 'tr' ? ' selected="selected"' : '' ?>>tr</option>
						</select>
						<span class="description"><?php _e('Default language for filtering stopwords', 'keyword-statistics') ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Integrate content language in meta informations', 'keyword-statistics') ?></th>
					<td>
						<input type="checkbox" name="ks_set_language_meta" value="1" <?php echo $options['set_language_meta'] == 1 ? 'checked="checked"' : '' ?> /><br/>
						<span class="description"><?php _e('If your blog contains content in languages not listed in the selection above or you are planning to write posts or pages in not supported languages, you should deactivate this option. This will prevent from sending the false content language and let the client check it automatically.', 'keyword-statistics') ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Filter stopwords', 'keyword-statistics') ?></th>
					<td>
						<input type="checkbox" name="ks_filter_stopwords" value="1" <?php echo $options['filter_stopwords'] == 1 ? 'checked="checked"' : '' ?> />
						<span class="description"><?php _e('Activate the stopword-filter by default when post/page-editor will be opened', 'keyword-statistics') ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Items in keyword lists', 'keyword-statistics') ?></th>
					<td>
						<select name="ks_max_list_items">
							<?php for ($i = 1; $i < 11; $i++) echo '<option value="' . $i . '" ' . ($options['max_list_items'] == $i ? 'selected="selected"' : '') . '>' . $i . '</option>'; ?>
						</select>
						<span class="description"><?php _e('Display this number of the most common keywords / key-phrases', 'keyword-statistics') ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Automatic Update', 'keyword-statistics') ?></th>
					<td>
						<input type="checkbox" name="ks_automatic_update" value="1" <?php echo $options['automatic_update'] == 1 ? 'checked="checked"' : '' ?> /><br/>
						<span class="description"><?php _e('Should the statistics refresh automatically? Turn off this option, if you have got slow JavaScript-Performance and use the button in the various edit-views instead!', 'keyword-statistics') ?></span><br/>
						<?php _e('every', 'keyword-statistics') ?> <select name="ks_update_interval">
							<?php for ($i = 1; $i < 11; $i++) echo '<option value="' . $i . '" ' . ($options['update_interval'] == $i ? 'selected="selected"' : '') . '>' . $i . '</option>'; ?>
						</select> <?php _e('seconds', 'keyword-statistics') ?>
						<span class="description"><?php _e('Number of seconds between the updates', 'keyword-statistics') ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Minimum number of words per post or page', 'keyword-statistics') ?></th>
					<td>
						<select name="ks_min_words">
							<?php for ($i = 50; $i < 500; $i+= 10) echo '<option value="' . $i . '" ' . ($options['min_words'] == $i ? 'selected="selected"' : '') . '>' . $i . '</option>'; ?>
						</select><br/>
						<span class="description"><?php _e('If there is no content it doesn\'t make sense to generate meta informations from it. Here you can set the minimum number of words in a post or page that should be reached before keywords and description meta will be generated.', 'keyword-statistics') ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Keyword Phrases', 'keyword-statistics') ?></th>
					<td>
						<input type="checkbox" name="ks_2word_phrases" value="1" <?php echo $options['2word_phrases'] == 1 ? 'checked="checked"' : '' ?> /> <?php _e('show 2-word phrases', 'keyword-statistics') ?><br/>
						<input type="checkbox" name="ks_3word_phrases" value="1" <?php echo $options['3word_phrases'] == 1 ? 'checked="checked"' : '' ?> /> <?php _e('show 3-word phrases', 'keyword-statistics') ?><br/>
						<span class="description"><?php _e('Switching on or off statistics for 2- and 3-word phrases (if your browser\'s JavaScript-Engine ist too slow and it is disturbing while writing)', 'keyword-statistics') ?></span>
					</td>
				</tr>
			</table>
			<p class="submit"><input type="submit" name="Submit" value="<?php _e('Update') ?>" /></p>
		</form>
	</div> <?php
}

// settings for automatic metadata generation
function ks_additional_options_metadata_generation () {
	$options = keyword_statistics_get_plugin_configuration ();
	if (isset ($_POST['ks_update_options']) && $_POST['ks_update_options'] == 1) {
		if (!wp_verify_nonce ($_POST['ks_meta_generation_nonce'], 'ks_meta_generation_nonce')) {
			$err = 1;
			$errmsg = __('Nonce verification failed:', 'keyword-statistics') . ' ' . __('Error while saving options for automatic meta data generation!', 'keyword-statistics');
		}
		else {
			$options['automatic_meta_data_update'] = 0;
			foreach ($_POST as $key => $value) {
				switch ($key) {
					case 'ks_automatic_meta_data_update':
						$options['automatic_meta_data_update'] = 1;
						break;
					case 'ks_words_from_posts_in_aggregated_description':
					case 'ks_max_title_length':
					case 'ks_max_keywords_count':
					case 'ks_meta_keywords_count':
					case 'ks_max_keywords_length':
					case 'ks_min_description_length':
					case 'ks_max_description_length':
						if (preg_match ('/[0-9]+/', $value))
							$options[substr ($key, 3)] = $value;
						else {
							$err = 1;
							//$errmsg = __('', 'keyword-statistics');
						}
						break;
				}
			}
		}
		// show status message
		if (!isset ($err)) { 
			// update options in database if there was no input error
			keyword_statistics_set_plugin_configuration ($options);
			?> <div class="updated"><p><strong><?php _e('Options saved.'); ?></strong></p></div> <?php
		}
		else { ?> <div class="error"><p><strong><?php echo __('Options not saved', 'keyword-statistics') . (isset ($errmsg) ? ' - ' . $errmsg : '!'); ?></strong></p></div> <?php }
	} ?>
	<div class="wrap"><div id="icon-options-general" class="icon32"><br /></div>
		<h2><?php _e('Automatic Metadata Generation', 'keyword-statistics') ?></h2>
		<form name="ks_options" method="post" action="">
			<input type="hidden" name="ks_update_options" value="1" />
			<input type="hidden" name="ks_meta_generation_nonce" value="<?php echo wp_create_nonce ('ks_meta_generation_nonce') ?>" />
			<table class="form-table">
				<tr><td colspan="2"><span class="description"><?php _e('Here you can configure if the keyword statistics plugin should automatically set meta keywords and description of single posts or pages at edit-page/post. Moreover you can set some boundaries for the characters or keys used in the keywords and description meta tags. These values are used when the plugin is generating aggregated values for the tags. At edit-page/post these values are used to warn the author when he is using too many characters or words.', 'keyword-statistics') ?></span></td></tr>
				<tr valign="top">
					<th scope="row"><?php _e('Update meta data automatically', 'keyword-statistics') ?></th>
					<td>
						<input type="checkbox" name="ks_automatic_meta_data_update" value="1" <?php echo $options['automatic_meta_data_update'] == 1 ? 'checked="checked"' : '' ?> /><br />
						<span class="description"><?php _e('Uncheck this option, if you want to edit the fields with the meta informations manually. If one of the fields is editable and has been edited, the automatic actualization will be disabled.', 'keyword-statistics') ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Words for aggregated descriptions', 'keyword-statistics') ?></th>
					<td>
						<select name="ks_words_from_posts_in_aggregated_description">
							<?php for ($i = 0; $i < 10; $i++) echo '<option value="' . $i . '" ' . ($options['words_from_posts_in_aggregated_description'] == $i ? 'selected="selected"' : '') . '>' . $i . '</option>'; ?>
						</select><br />
						<span class="description"><?php _e('Set this to 0 if no automatic integration of a meta description is wanted in aggregation-views.', 'keyword-statistics') ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Maximum number of characters in title tags', 'keyword-statistics') ?></th>
					<td>
						<select name="ks_max_title_length">
							<?php for ($i = 50; $i < 80; $i++) echo '<option value="' . $i . '" ' . ($options['max_title_length'] == $i ? 'selected="selected"' : '') . '>' . $i . '</option>'; ?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Maximum number of keywords/phrases in keyword tags', 'keyword-statistics') ?></th>
					<td>
						<select name="ks_max_keywords_count">
							<?php for ($i = 5; $i < 20; $i++) echo '<option value="' . $i . '" ' . ($options['max_keywords_count'] == $i ? 'selected="selected"' : '') . '>' . $i . '</option>'; ?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Number of meta keywords generated in posts or pages', 'keyword-statistics') ?></th>
					<td>
						<select name="ks_meta_keywords_count">
							<?php for ($i = 1; $i < 11; $i++) echo '<option value="' . $i . '" ' . ($options['meta_keywords_count'] == $i ? 'selected="selected"' : '') . '>' . $i . '</option>'; ?>
						</select><br />
						<span class="description"><?php _e('For generating a list of suggested keywords for setting the meta-keyword-tag of a single post or page.', 'keyword-statistics') ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Maximum number of characters in keyword tags', 'keyword-statistics') ?></th>
					<td>
						<select name="ks_max_keywords_length">
							<?php for ($i = 50; $i < 120; $i++) echo '<option value="' . $i . '" ' . ($options['max_keywords_length'] == $i ? 'selected="selected"' : '') . '>' . $i . '</option>'; ?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Minimum number of characters in meta description', 'keyword-statistics') ?></th>
					<td>
						<select name="ks_min_description_length">
							<?php for ($i = 80; $i < 161; $i++) echo '<option value="' . $i . '" ' . ($options['min_description_length'] == $i ? 'selected="selected"' : '') . '>' . $i . '</option>'; ?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Maximum number of characters in meta description', 'keyword-statistics') ?></th>
					<td>
						<select name="ks_max_description_length">
							<?php for ($i = 80; $i < 161; $i++) echo '<option value="' . $i . '" ' . ($options['max_description_length'] == $i ? 'selected="selected"' : '') . '>' . $i . '</option>'; ?>
						</select>
					</td>
				</tr>
			</table>
			<p class="submit"><input type="submit" name="Submit" value="<?php _e('Update') ?>" /></p>
		</form>
	</div> <?php
}

function ks_robots_feed () {
	$options = keyword_statistics_get_plugin_configuration ();
	if ($options['feeds_noindex'] == 1)
		echo "\t<xhtml:meta xmlns:xhtml=\"http://www.w3.org/1999/xhtml\" name=\"robots\" content=\"noindex\" />\n";
}
add_action ('rss_head', 'ks_robots_feed');
add_action ('rss2_head', 'ks_robots_feed');
add_action ('atom_head', 'ks_robots_feed');
add_action ('comments_atom_head', 'ks_robots_feed');
add_action ('commentsrss2_head', 'ks_robots_feed');
add_action ('rdf_header', 'ks_robots_feed');

// Header for stylesheets and scripts
function keyword_statistics_admin_header () {
	global $wp, $ks_plugin_dir;
	?>
	<script src="../wp-content/plugins/<?php echo $ks_plugin_dir ?>/stopwords.js" type="text/javascript"></script>
	<script src="../wp-content/plugins/<?php echo $ks_plugin_dir ?>/textstat.js" type="text/javascript"></script>
	<style type="text/css">
	/* <![CDATA[ */
	#keystats { font-size:13px; color:#333 line-height:12px }
	#keystats table, .inside table.ksmeta { width:100%; background-color:#f9f9f9; color:#333; line-height:12px; border:1px solid #f1f1f1 }
	#keystats th { background-color:#f1f1f1; padding:6px 1em; }
	#keystats td { padding:2px 0.5em; }
	#keystats u { font-weight:bold }
	/* ]]> */
	</style>
	<?php
}
add_action ('admin_head', 'keyword_statistics_admin_header');

// Footer
function keyword_statistics_admin_footer () {
	global $post;
	//$meta = get_post_meta ($post->ID, 'ks_metadata', true);
	$options = keyword_statistics_get_plugin_configuration ();?>
	<script type="text/javascript">
	/* <![CDATA[ */
	function updateTextInfo () {
		if (!document.getElementById ('kslang').value)
			return;
		var lang = document.getElementById ('kslang').value;
		var textfield = document.getElementById ('content');
		if (!textfield || typeof textfield != 'object' || textfield.type != 'textarea')
			return;
		if (!typeof lang == 'string')
			return;
		if (textfield.lang)
			if (typeof stopwords[textfield.lang] == 'object')
				language = textfield.lang;
		// we have to initialize the number of keywords that should be generated for automatic keywords suggestion
		var keycount = <?php echo $options['meta_keywords_count'] ?>;
		// output template
		var template = '<u><?php _e('Text Statistics', 'keyword-statistics') ?></u>' +
			       '<table>' +
			       '<thead><tr><th>' + (document.getElementById ('ksfilter').checked ? '<?php _e('Total Words with / without Stopwords', 'keyword-statistics') ?>' : '<?php _e('Total Words', 'keyword-statistics') ?>') + '</th><th>' + (document.getElementById ('ksfilter').checked ? '<?php _e('Different Words with / without Stopwords', 'keyword-statistics') ?>' : '<?php _e('Different Words', 'keyword-statistics') ?>') + '</th><th><?php _e('Stopwords', 'keyword-statistics') ?></th></tr></thead>' +
			       '<tbody><tr><td style="text-align:center">[WORDCOUNT]' + (document.getElementById ('ksfilter').checked ? ' / [WORDCOUNT_FILTERED]' : '') +'</td><td style="text-align:center">[WORDCOUNT_DIFFERENT]' + (document.getElementById ('ksfilter').checked ? ' / [WORDCOUNT_DIFFERENT_FILTERED]' : '') + '</td><td style="text-align:center">[WORDCOUNT_STOPWORDS]</td></tr></tbody>' +
			       '</table>' +
			       '[KEYS' + (document.getElementById ('ksfilter').checked ? '_FILTERED' : '') + ':1:' + document.getElementById ('kslines').value + ']' +
			       <?php if ($options['2word_phrases'] == 1) echo '\'[KEYS\' + (document.getElementById (\'ksfilter\').checked ? \'_FILTERED\' : \'\') + \':2:\' + document.getElementById (\'kslines\').value + \']\' +'; ?>
			       <?php if ($options['3word_phrases'] == 1) echo '\'[KEYS\' + (document.getElementById (\'ksfilter\').checked ? \'_FILTERED\' : \'\') + \':3:\' + document.getElementById (\'kslines\').value + \']\' +'; ?>
			       '';//'<u><?php _e('Meta Keywords Suggestion', 'keyword-statistics') ?></u>: [KEYWORDS:<?php echo $options['meta_keywords_count'] ?>]';
		// replace template variables
		// do we have an instance of tinyMCE?
		if (typeof tinyMCE != 'undefined' && tinyMCE.activeEditor)
			// need to save the content of editor before we can read it
			tinyMCE.triggerSave();
		// get content to analyze out of the textarea, filter caption-blocks and any other shortcodes before analysis
		var t = new TextStatistics (textfield.value.replace (/\[caption.*caption=\"([^"]*)\"[^\]]*](.*)\[\/caption\]/ig, " $1 $2 ").replace (/\[[^\]]+\/?\]/ig, ""), lang);
		if (template.match (/\[WORDCOUNT\]/ig))
			template = template.replace (/\[WORDCOUNT\]/ig, t.getWordCount ());
		if (template.match (/\[WORDCOUNT_FILTERED\]/ig))
			template = template.replace (/\[WORDCOUNT_FILTERED\]/ig, t.getWordCount (true));
		if (template.match (/\[WORDCOUNT_DIFFERENT\]/ig))
			template = template.replace (/\[WORDCOUNT_DIFFERENT\]/ig, t.getDifferentWordCount ());
		if (template.match (/\[WORDCOUNT_DIFFERENT_FILTERED\]/ig))
			template = template.replace (/\[WORDCOUNT_DIFFERENT_FILTERED\]/ig, t.getDifferentWordCount (true));
		if (template.match (/\[WORDCOUNT_STOPWORDS\]/ig))
			template = template.replace (/\[WORDCOUNT_STOPWORDS\]/ig, t.getStopWordCount ());
		if (template.match (/\[LANGUAGE\]/ig))
			template = template.replace (/\[LANGUAGE\]/ig, t.getLanguage ());
		//if (template.match (/\[KEYWORDS:([0-9]+)\]/ig)) {
		//	keycount = parseInt (RegExp.$1);
		//	template = template.replace (/\[KEYWORDS:[0-9]+\]/ig, t.getKeywordList (keycount));
		//}
		var startString = '';
		// replace key informations in template
		var m = template.match (/\[KEYS(_FILTERED)?:[1-5]:[0-9]+\]/ig);
		if (m)
			for (var i = 0; i < m.length; i++) {
				var filtered = m[i].match (/_FILTERED/i) ? true : false;
				m[i].match (/[^:]:([1-5]):([0-9]+)/);
				var keylength = parseInt (RegExp.$1);
				var maxkeys = parseInt (RegExp.$2);
				var stats = t.getStats (keylength, filtered);
				if (stats.keys.length > 0) {
					var table = '<u><br/>' + (keylength == 1 ? '<?php _e('Single Words', 'keyword-statistics') ?>' : (keylength == 2 ? '<?php _e('2 Word Phrases', 'keyword-statistics') ?>' : '<?php _e('3 Word Phrases', 'keyword-statistics') ?>')) + '</u>' +
						    '<table><thead><tr><th style="width:6em;"><?php _e('Count', 'keyword-statistics') ?></th><th style="width:10em;"><?php _e('Density', 'keyword-statistics') ?></th><th><?php _e('Keyword / Phrase', 'keyword-statistics') ?></th></tr></thead>' +
						    '<tfoot><tr><td colspan="3"><u><?php _e('Total / Different', 'keyword-statistics') ?></u>: ' + stats.keycount + ' / ' + stats.different + '</td></tr></tfoot><tbody>';
					for (var j = 0; j < (stats.keys.length < maxkeys ? stats.keys.length : maxkeys); j++)
						table += '<tr><td style="text-align:right">' + stats.keys[j].getCount () + '</td><td style="text-align:right">' + stats.keys[j].getDensity () + '</td><td>' + stats.keys[j].getKey () + '</td></tr>';
					table += '</tbody></table>';
					template = template.replace (m[i], table);
					// get most important keyword and 2-word keyword phrase for generation of the page description
					if (keylength < 3)
						startString += stats.keys[0].getKey () + (keylength == 1 ? '|' : '');
				}
				else
					template = template.replace (m[i], '<u>' + (keylength == 1 ? '<?php _e('No single keywords found', 'keyword-statistics') ?>' : (keylength == 2 ? '<?php _e('No 2-word phrases found', 'keyword-statistics') ?>' : '<?php _e('No 3-word phrases found', 'keyword-statistics') ?>')) + '</u><br/><br/>');
			}
		// output of collected information
		document.getElementById ('keystats').innerHTML = template;
		// we should have some text for generation of meta informations
		if (t.getWordCount () >= <?php echo $options['min_words'] ?>) {
			if (document.getElementById ('keywords_autoupdate').checked || (document.getElementById ('keywords_autoupdate').type == 'hidden' && document.getElementById ('keywords_autoupdate').value == 'ks_kau')) {
				document.getElementById ('kskeywords').value = t.getKeywordList (keycount);
				// update status field
				fieldStatus (document.getElementById ('kskeywords'));
				if (document.getElementById ('kskeywords_view'))
					document.getElementById ('kskeywords_view').value = t.getKeywordList (keycount);
			}
			if (document.getElementById ('description_autoupdate').checked || (document.getElementById ('description_autoupdate').type == 'hidden' && document.getElementById ('description_autoupdate').value == 'ks_dau')) {
				if (startString.length != 0) {
					var single = startString.replace (/\|.*$/, '');
					var phrase = startString.replace (/^[^|]+\|/, '');
					var description = '';
						// we take the first occurance of the word and some additional...
						m = t.getContent ().match (new RegExp ('(' + single + '[\\s-,.:;!?]+([^\\s]+[\\s-,.:;!?]+){30})', 'gi'));
						if (m) {
							while (m[0].length >= <?php echo $options['max_description_length'] . (strlen ($options['sitewide_description_predecessor']) != 0 ? ' - ' . strlen ($options['sitewide_description_predecessor']) : '') . (strlen ($options['sitewide_description_successor']) != 0 ? ' - ' . strlen ($options['sitewide_description_successor']) : '') ?>)
								m[0] = m[0].substr (0, m[0].lastIndexOf (' '));
							description = m[0];
						}
					document.getElementById ('ksdescription').value = description;
					// update status field
					fieldStatus (document.getElementById ('ksdescription'));
					if (document.getElementById ('ksdescription_view'))
						document.getElementById ('ksdescription_view').value = description;
				}
			}
		}
		// otherwise we drop metadata if autoupdate is on - keywords and a description are senseless without content
		else {
			if (document.getElementById ('keywords_autoupdate').checked || (document.getElementById ('keywords_autoupdate').type == 'hidden' && document.getElementById ('keywords_autoupdate').value == 'ks_kau')) {
				document.getElementById ('kskeywords').value = '';
				// update status field
				fieldStatus (document.getElementById ('kskeywords'));
				if (document.getElementById ('kskeywords_view'))
					document.getElementById ('kskeywords_view').value = '';
			}
			if (document.getElementById ('description_autoupdate').checked || (document.getElementById ('description_autoupdate').type == 'hidden' && document.getElementById ('description_autoupdate').value == 'ks_dau')) {
				document.getElementById ('ksdescription').value = '';
				// update status field
				fieldStatus (document.getElementById ('ksdescription'));
				if (document.getElementById ('ksdescription_view'))
					document.getElementById ('ksdescription_view').value = '';
			}
		}
	}
	function eatKeys (event) {
		key = (event.which ? event.which : event.keyCode);
		if ((key < 41 || key == 45) && key != 8 && key != 13 && key != 32 && key != 0)
			return false;
		return true;
	}
	function disableKeywordsAutoUpdate (event) {
		if (eatKeys (event))
			document.getElementById ('keywords_autoupdate').checked = '';
		return event;
	}
	function disableDescriptionAutoUpdate (event) {
		if (eatKeys (event))
			document.getElementById ('description_autoupdate').checked = '';
		return event;
	}
	if (document.getElementById ('kskeywords'))
		document.getElementById ('kskeywords').onkeydown = disableKeywordsAutoUpdate;
	if (document.getElementById ('ksdescription'))
		document.getElementById ('ksdescription').onkeydown = disableDescriptionAutoUpdate;

	if (document.getElementById ('publish'))
		document.getElementById ('publish').onclick = updateTextInfo;
	if (document.getElementById ('save-post'))
		document.getElementById ('save-post').onclick = updateTextInfo;
	/* ]]> */
	</script>
	<?php
}
add_action ('admin_footer', 'keyword_statistics_admin_footer');
?>