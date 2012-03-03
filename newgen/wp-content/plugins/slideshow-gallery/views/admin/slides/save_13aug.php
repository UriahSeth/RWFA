<div class="wrap">
	<h2><?php _e('Save a Slide', $this -> plugin_name); ?></h2>
	
	<form action="<?php echo $this -> url; ?>&amp;method=save" method="post" enctype="multipart/form-data">
		<input type="hidden" name="Slide[id]" value="<?php echo $this -> Slide -> data -> id; ?>" />
		<input type="hidden" name="Slide[order]" value="<?php echo $this -> Slide -> data -> order; ?>" />
	<script language="javascript">
	function setDescription(val)
	{
		jQuery("#slide_description").val(val + " - Roger Williams Fine Art");
	}
	</script>
		<table class="form-table">
			<tbody>
				<tr>
					<th><label for="Slide.title"><?php _e('Title', $this -> plugin_name); ?></label></th>
					<td>
						<input class="widefat" type="text" name="Slide[title]" value="<?php echo esc_attr($this -> Slide -> data -> title); ?>" id="Slide.title" onchange="setDescription(this.value)"/>
                        <span class="howto"><?php _e('title/name of your slide as it will be displayed to your users.', $this -> plugin_name); ?></span>
						<?php echo (!empty($this -> Slide -> errors['title'])) ? '<div style="color:red;">' . $this -> Slide -> errors['title'] . '</div>' : ''; ?>
					</td>
				</tr>
                <tr>
					<th><label for="Slide.image_width"><?php _e('Width', $this -> plugin_name); ?></label></th>
					<td>
						<input class="widefat" type="text" name="Slide[image_width]" value="<?php echo esc_attr($this -> Slide -> data -> image_width); ?>" id="Slide.image_width" style="width:50px" maxlength="4"/>
                        <span class="howto"><?php _e('width in inches.', $this -> plugin_name); ?></span>
						<?php echo (!empty($this -> Slide -> errors['image_width'])) ? '<div style="color:red;">' . $this -> Slide -> errors['image_width'] . '</div>' : ''; ?>
					</td>
				</tr>
                <tr>
					<th><label for="Slide.image_height"><?php _e('Height', $this -> plugin_name); ?></label></th>
					<td>
						<input class="widefat" type="text" name="Slide[image_height]" value="<?php echo esc_attr($this -> Slide -> data -> image_height); ?>" id="Slide.image_height"  style="width:50px" maxlength="4"/>
                        <span class="howto"><?php _e('height in inches.', $this -> plugin_name); ?></span>
						<?php echo (!empty($this -> Slide -> errors['image_height'])) ? '<div style="color:red;">' . $this -> Slide -> errors['image_height'] . '</div>' : ''; ?>
					</td>
				</tr>
                <input type="hidden" class="widefat" id="slide_description" name="Slide[description]" value="<?php echo esc_attr($this -> Slide -> data -> description); ?>">
				<!--<tr>
					<th><label for="Slide.description"><?php _e('Description', $this -> plugin_name); ?></label></th>
					<td>
						<textarea class="widefat" name="Slide[description]"><?php echo esc_attr($this -> Slide -> data -> description); ?></textarea>
                        <span class="howto"><?php //_e('description of your slide as it will be displayed to your users below the title.', $this -> plugin_name); ?></span>
						<?php // echo (!empty($this -> Slide -> errors['description'])) ? '<div style="color:red;">' . $this -> Slide -> errors['description'] . '</div>' : ''; ?>
					</td>
				</tr>-->
                <tr>
					<th><label for="Slide.location"><?php _e('Location/Medium', $this -> plugin_name); ?></label></th>
					<td>
						<textarea class="widefat" name="Slide[location]"><?php echo esc_attr($this -> Slide -> data -> location); ?></textarea>
                        <span class="howto"><?php _e('location of your slide will be displayed.', $this -> plugin_name); ?></span>
						<?php echo (!empty($this -> Slide -> errors['location'])) ? '<div style="color:red;">' . $this -> Slide -> errors['location'] . '</div>' : ''; ?>
					</td>
				</tr>
                <tr>
                	<th><label for="Slide.type.file"><?php _e('Image Type', $this -> plugin_name); ?></label></th>
                    <td>
                    	<label><input onclick="jQuery('#typediv_file').show(); jQuery('#typediv_url').hide();" <?php echo (empty($this -> Slide -> data -> type) || $this -> Slide -> data -> type == "file") ? 'checked="checked"' : ''; ?> type="radio" name="Slide[type]" value="file" id="Slide.type.file" /> <?php _e('Upload File (recommended)', $this -> plugin_name); ?></label>
                        <label><input onclick="jQuery('#typediv_url').show(); jQuery('#typediv_file').hide();" <?php echo ($this -> Slide -> data -> type == "url") ? 'checked="checked"' : ''; ?> type="radio" name="Slide[type]" value="url" id="Slide.type.url" /> <?php _e('Specify URL', $this -> plugin_name); ?></label>
                        <?php echo (!empty($this -> Slide -> errors['type'])) ? '<div style="color:red;">' . $this -> Slide -> errors['type'] . '</div>' : ''; ?>
                        <span class="howto"><?php _e('do you want to upload an image or specify a local/remote image URL?', $this -> plugin_name); ?></span>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <div id="typediv_file" style="display:<?php echo (empty($this -> Slide -> data -> type) || $this -> Slide -> data -> type == "file") ? 'block' : 'none'; ?>;">
        	<table class="form-table">
            	<tbody>
                	<tr>
                    	<th><label for="Slide.image_file"><?php _e('Choose Image', $this -> plugin_name); ?></label></th>
                        <td>
                        	<input type="file" name="image_file" value="" id="Slide.image_file" />
                            <span class="howto"><?php _e('choose your image file from your computer. JPG, PNG, GIF are supported.', $this -> plugin_name); ?></span>
                            <?php echo (!empty($this -> Slide -> errors['image_file'])) ? '<div style="color:red;">' . $this -> Slide -> errors['image_file'] . '</div>' : ''; ?>
                            
                            <?php
							
							if (!empty($this -> Slide -> data -> type) && $this -> Slide -> data -> type == "file") {
								if (!empty($this -> Slide -> data -> image)) {
									$name = $this -> Html -> strip_ext($this -> Slide -> data -> image, 'filename');
									$ext = $this -> Html -> strip_ext($this -> Slide -> data -> image, 'ext');
									
									?>
                                    
                                    <input type="hidden" name="Slide[image_oldfile]" value="<?php echo esc_attr(stripslashes($this -> Slide -> data -> image)); ?>" />
                                    <p><small><?php _e('Current image. Leave the field above blank to keep this image.', $this -> plugin_name); ?></small></p>
                                    <img src="<?php echo rtrim(get_bloginfo('wpurl'), '/'); ?>/wp-content/uploads/<?php echo $this -> plugin_name; ?>/<?php echo $name; ?>-thumb.<?php echo $ext; ?>" alt="" />
                                    
                                    <?php	
								}
							}
							
							?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div id="typediv_url" style="display:<?php echo ($this -> Slide -> data -> type == "url") ? 'block' : 'none'; ?>;">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th><label for="Slide.image_url"><?php _e('Image URL', $this -> plugin_name); ?></label></th>
                        <td>
                            <input class="widefat" type="text" name="Slide[image_url]" value="<?php echo esc_attr($this -> Slide -> data -> image_url); ?>" id="Slide.image_url" />
                            <span class="howto"><?php _e('Local or remote image location eg. http://domain.com/path/to/image.jpg', $this -> plugin_name); ?></span>
                            <?php echo (!empty($this -> Slide -> errors['image_url'])) ? '<div style="color:red;">' . $this -> Slide -> errors['image_url'] . '</div>' : ''; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>    
                
        <table class="form-table">
        	<tbody>
				<tr>
					<th><label for="Slide_userlink_N"><?php _e('For Sale', $this -> plugin_name); ?></label></th>
					<td>
						<label><input <?php echo ($this -> Slide -> data -> uselink == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="Slide[uselink]" value="Y" id="Slide_uselink_Y" /> <?php _e('Yes', $this -> plugin_name); ?></label>
						<label><input  <?php echo (empty($this -> Slide -> data -> uselink) || $this -> Slide -> data -> uselink == "N") ? 'checked="checked"' : ''; ?> type="radio" name="Slide[uselink]" value="N" id="Slide_uselink_N" /> <?php _e('No', $this -> plugin_name); ?></label>
                        <span class="howto"><?php _e("set this to Yes to 'For Sale' this Image.", $this -> plugin_name); ?></span>
					</td>
				</tr>
			</tbody>
		</table>
        
        <table class="form-table">
        	<tbody>
				<tr>
					<th><label for="Slide_userlink_N"><?php _e('Show at Home', $this -> plugin_name); ?></label></th>
					<td>
						<label><input <?php echo ($this -> Slide -> data -> show_at_home == "1") ? 'checked="checked"' : ''; ?> type="checkbox" name="Slide[show_at_home]" value="1" id="show_at_home" /> <?php _e('Yes', $this -> plugin_name); ?></label>
					
                        <span class="howto"><?php _e("check this to set it at home.", $this -> plugin_name); ?></span>
					</td>
				</tr>
			</tbody>
		</table>
		
		<div id="Slide_uselink_div" style="display:none;">
			<table class="form-table">
				<tbody>
					<tr>
						<th><label for="Slide.link"><?php _e('Link To', $this -> plugin_name); ?></label></th>
						<td>
                        	<input class="widefat" type="text" name="Slide[link]" value="<?php echo esc_attr($this -> Slide -> data -> link); ?>" id="Slide.link" />
                            <span class="howto"><?php _e('link/URL to go to when a user clicks the slide eg. http://www.domain.com/mypage/', $this -> plugin_name); ?></span>
                        </td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<p class="submit">
			<input class="button-primary" type="submit" name="submit" value="<?php _e('Save Slide', $this -> plugin_name); ?>" />
		</p>
	</form>
</div>