<?php
/* wppa_widget.php
* Package: wp-photo-album-plus
*
* display the widget
* Version 3.0.5
*/

class PhotoOfTheDay extends WP_Widget {
    /** constructor */
    function PhotoOfTheDay() {
        parent::WP_Widget(false, $name = 'Photo Of The Day');	
		$widget_ops = array('classname' => 'wppa_widget', 'description' => __( 'WPPA+ Photo Of The Day', 'wppa') );	//
		$this->WP_Widget('wppa_widget', __('Photo Of The Day', 'wppa'), $widget_ops);															//
    }

	/** @see WP_Widget::widget */
    function widget($args, $instance) {		
		global $wpdb;
		global $widget_content;

        extract( $args );

		$widget_title = apply_filters('widget_title', empty( $instance['title'] ) ? get_option('wppa_widgettitle', __a( 'Photo Of The Day', 'wppa_theme' )) : $instance['title']);

		// get the photo  ($image)
		switch (get_option('wppa_widget_method', '1')) {
			case '1':	// Fixed photo
				$id = get_option('wppa_widget_photo', '');
				if ($id != '') {
					$image = $wpdb->get_row($wpdb->prepare('SELECT * FROM `' . WPPA_PHOTOS . '` WHERE `id` = %d LIMIT 0,1', $id), 'ARRAY_A');
				}
				break;
			case '2':	// Random
				$album = get_option('wppa_widget_album', '');
				if ($album != '') {
					$images = wppa_get_widgetphotos($album, 'ORDER BY RAND() LIMIT 0,1');
					$image = $images[0];
				}
				break;
			case '3':	// Last upload
				$album = get_option('wppa_widget_album', '');
				if ($album != '') {
					$images = wppa_get_widgetphotos($album, 'ORDER BY id DESC LIMIT 0,1');
					$image = $images[0];
				}
				break;
			case '4':	// Change every
				$album = get_option('wppa_widget_album', '');
				if ($album != '') {
					$per = get_option('wppa_widget_period', '168');
					$photos = wppa_get_widgetphotos($album);
					if ($per == '0') {
						if ($photos) {
							$image = $photos[rand(0, count($photos)-1)];
						}
						else $image = '';
					}
					else {
						$u = date("U"); // Seconds since 1-1-1970
						$u /= 3600;		//  hours since
						$u = floor($u);
						$u /= $per;
						$u = floor($u);
						if ($photos) {
							$p = count($photos); 
							$idn = fmod($u, $p);
							$image = $photos[$idn];
						}
						else {
							$image = '';
						}
					}
				} else {
					$image = '';
				}
				break;
			default:
				$image = '';
		}
		
		// Make the HTML for current picture
		$widget_content = '<div class="wppa-widget" style="'.__wcs('wppa-widget').'">';
		if ($image) {
			// make image url
			$imgurl = get_bloginfo('wpurl') . '/wp-content/uploads/wppa/' . $image['id'] . '.' . $image['ext'];
		
			$name = wppa_qtrans($image['name']);
			$link = wppa_get_imglnk_a('potdwidget', $image['id']);
			
			if ($link) $widget_content .= '<a href = "'.$link['url'].'" title="'.$link['title'].'">';
			
				$widget_content .= '<img src="'.$imgurl.'" style="width: '.get_option('wppa_widget_width', '190').'px;" alt="'.$name.'">';

			if ($link) $widget_content .= '</a>';
		} 
		else {	// No image
			$widget_content .= __a('Photo not found.', 'wppa_theme');
		}
		$widget_content .= '</div>';
		// Add subtitle, if any		
		switch (get_option('wppa_widget_subtitle', 'none'))
		{
			case 'none': 
				break;
			case 'name': 
				if ($image && $image['name'] != '') {
					$widget_content .= '<div class="wppa-widget-text">' . wppa_qtrans(wppa_html(stripslashes($image['name']))) . '</div>';
				}
				break;
			case 'desc': 
				if ($image && $image['description'] != '') {
					$widget_content .= '<div class="wppa-widget-text">' . wppa_qtrans(wppa_html(stripslashes($image['description']))) . '</div>'; 
				}
				break;
		}

		echo $before_widget . $before_title . $widget_title . $after_title . $widget_content . $after_widget;
    }
	
    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
		//Defaults
		$instance = wp_parse_args( (array) $instance, array(  'title' => '') );
		$widget_title = apply_filters('widget_title', empty( $instance['title'] ) ? get_option('wppa_widgettitle', __( 'Photo Of The Day', 'wppa' )) : $instance['title']);

	?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wppa'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $widget_title; ?>" /></p>
		<p><?php _e('You can set the content and the behaviour of this widget in the <b>Photo Albums -> Sidebar Widget</b> admin page.', 'wppa'); ?></p>
<?php
    }

} // class PhotoOfTheDay

require_once ('wppa_widgetfunctions.php');

// register PhotoOfTheDay widget
add_action('widgets_init', create_function('', 'return register_widget("PhotoOfTheDay");'));
