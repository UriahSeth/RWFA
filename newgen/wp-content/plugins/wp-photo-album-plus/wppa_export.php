<?php 
/* wppa_export.php
* Package: wp-photo-album-plus
*
* Contains all the export functions
* Version 3.0.2
*/

function wppa_page_export() {
global $wpdb;

	// export images admin page

	// Check the existence of required directories
	if (!wppa_check_dirs()) return;

    // sanitize system
	wppa_cleanup_photos();
	
	// Check if an update message is required
	wppa_check_update();

	// Do the export if requested
	if (isset($_POST['wppa-export-submit'])) {
		wppa_check_admin_referer( '$wppa_nonce', WPPA_NONCE );
		wppa_export_photos();
	} ?>
	<div class="wrap">
		<?php $iconurl = get_bloginfo('wpurl') . '/wp-content/plugins/' . WPPA_PLUGIN_PATH . '/images/album32.png'; ?>
		<div id="icon-camera" class="icon32" style="background: transparent url(<?php echo($iconurl); ?>) no-repeat">		
		</div>
		<?php $iconurl = get_bloginfo('wpurl') . '/wp-content/plugins/' . WPPA_PLUGIN_PATH . '/images/arrow32.png'; ?>
		<div id="icon-arrow" class="icon32" style="background: transparent url(<?php echo($iconurl); ?>) no-repeat">
		</div>
		<?php $iconurl = get_bloginfo('wpurl') . '/wp-content/plugins/' . WPPA_PLUGIN_PATH . '/images/disk32.png'; ?>
		<div id="icon-disk" class="icon32" style="background: transparent url(<?php echo($iconurl); ?>) no-repeat">
			<br />
		</div>

		<h2><?php _e('Export Photos', 'wppa'); ?></h2><br />

		<form action="<?php echo(get_admin_url()) ?>/admin.php?page=export_photos" method="post">
			<?php wppa_nonce_field('$wppa_nonce', WPPA_NONCE); ?>
			<?php echo(sprintf(__('Photos will be exported to: <b>%s</b>.', 'wppa'), 'wp-content/wppa-depot/'.wppa_get_user())) ?>
			<h2><?php _e('Export photos from album <span style="font-size:12px;">(Including Album information)</span>:', 'wppa'); ?></h2>
			<?php $albums = $wpdb->get_results('SELECT * FROM ' . WPPA_ALBUMS . ' ' . wppa_get_album_order(), 'ARRAY_A');
			$high = '0'; ?>
			
			<table class="form-table albumtable">
				<thead>
				</thead>
				<tbody>
				<tr>
					<?php $ct = 0;
					foreach($albums as $album) {
						$line = '&nbsp;'.$album['id'].':&nbsp;'.wppa_qtrans(stripslashes($album['name']));
						if ($album['id'] > $high) $high = $album['id']; ?>
						<td>
							<input type="checkbox" name="album-<?php echo($album['id']) ?>" />&nbsp;<?php echo($line) ?>
						</td>
						<?php if ($ct == 4) {	// Wrap to newline
							echo('</tr><tr>'); 
							$ct = 0;
						}
						else {
							$ct++;
						}
					} ?>
				</tr>
				</tbody>
			</table>
			<input type="hidden" name="high" value="<?php echo($high) ?>" />
			<p>
				<input type="submit" class="button-primary" name="wppa-export-submit" value="<?php _e('Export', 'wppa'); ?>" />
			</p>
		</form>
	</div>
<?php
}

function wppa_export_photos() {
global $wpdb;
global $wppa_zip;
global $wppa_temp;
global $wppa_temp_idx;

	$wppa_temp_idx = 0;

	_e('Exporting...<br/>', 'wppa');
	if (PHP_VERSION_ID >= 50207) {
		echo('Opening zip output file...');
		$wppa_zip = new ZipArchive;
		$zipid = get_option('wppa_last_zip', '0');
		$zipid++;
		update_option('wppa_last_zip', $zipid);
		$zipfile = ABSPATH.'wp-content/wppa-depot/'.wppa_get_user().'/wppa-'.$zipid.'.zip';
		if ($wppa_zip->open($zipfile, 1) === TRUE) { //ZipArchive::CREATE) === TRUE) {
			_e('ok, <br/>Filling', 'wppa'); echo(' '.basename($zipfile));
		} else {
			_e('failed<br/>', 'wppa');
			$wppa_zip = false;
//			return false;
		}
	}
	else {
		$wppa_zip = false;
	}
		
	if (isset($_POST['high'])) $high = $_POST['high']; else $high = 0;

	if ($high) {
		$id = 0;
		$cnt = 0;
		while ($id <= $high) {
			if (isset($_POST['album-'.$id])) {
				_e('<br/>Processing album', 'wppa'); echo(' '.$id.'....');
				wppa_write_album_file_by_id($id);
				$photos = $wpdb->get_results('SELECT * FROM ' . WPPA_PHOTOS . ' WHERE album = ' . $id, 'ARRAY_A');
				$cnt = 0;
				foreach($photos as $photo) {
					// Copy the photo
					$from = ABSPATH.'wp-content/uploads/wppa/'.$photo['id'].'.'.$photo['ext'];
					$to = ABSPATH.'wp-content/wppa-depot/'.wppa_get_user().'/'.$photo['id'].'.'.$photo['ext'];
						
					if ($wppa_zip) {
						$wppa_zip->addFile($from, basename($from));
					}
					else copy($from, $to);
					
					// Create the metadata
					if (!wppa_write_photo_file($photo)) {
						return false;
					}
					else $cnt++;
				} 
				_e('done.', 'wppa'); echo(' '.$cnt.' '); _e('photos processed.', 'wppa');
			}
			$id++;			
		}
		_e('<br/>Done export albums.', 'wppa');
	}
	else {
		_e('Nothing to export', 'wppa');
	}
	
	if ($wppa_zip) {
		_e('<br/>Closing zip.', 'wppa');
		_e('<br/>Deleting temp files.', 'wppa');
		$wppa_zip->close();
		// Now the zip is closed we can destroy all tempfiles we created here
		if (is_array($wppa_temp)) {
			foreach ($wppa_temp as $file) {
				unlink($file);
			}
		}
	}
	_e('<br/>Done!', 'wppa');
}

function wppa_write_album_file_by_id($id) {
global $wpdb;
global $wppa_zip;
global $wppa_temp;
global $wppa_temp_idx;
	$album = $wpdb->get_row('SELECT * FROM '.WPPA_ALBUMS.' WHERE id = '.$id.' LIMIT 0,1', 'ARRAY_A');
	if ($album) {
		$fname = ABSPATH.'wp-content/wppa-depot/'.wppa_get_user().'/'.$id.'.amf';
		$file = fopen($fname, 'wb');
		$err = false;
		if ($file) {
			if (fwrite($file, "name=".$album['name']."\n") !== FALSE) {
				if (fwrite($file, "desc=".$album['description']."\n") !== FALSE) {
					if (fwrite($file, "aord=".$album['a_order']."\n") !== FALSE) {
						if (fwrite($file, "prnt=".wppa_get_album_name($album['a_parent'], 'raw')."\n") !== FALSE) {
							if (fwrite($file, "pord=".$album['p_order_by']."\n") !== FALSE) {
								if (fwrite($file, "ownr=".$album['owner']."\n") !== FALSE) {
								}
								else $err = true;
							}
							else $err = true;
						}
						else $err = true;
					}
					else $err = true;
				}
				else $err = true;
			}
			else $err = true;
			if ($err) {
				wppa_error_message(sprintf(__('Cannot write to file %s.', 'wppa'),$fname));
				fclose($file);
				return false;
			}
			else {
				fclose($file);
				if (PHP_VERSION_ID >= 50207) {
					$wppa_zip->addFile($fname, basename($fname));
				}
				$wppa_temp[$wppa_temp_idx] = $fname;
				$wppa_temp_idx++;
			}
		}
		else {
			wppa_error_message(__('Could not open album output file.', 'wppa'));
			return false;
		}
	}
	else {
		wppa_error_message(__('Could not read album data.'), 'wppa');
		return false;
	}
	return true;
}

function wppa_write_photo_file($photo)	{
global $wppa_zip;
global $wppa_temp;
global $wppa_temp_idx;
	if ($photo) {
		$fname = ABSPATH.'wp-content/wppa-depot/'.wppa_get_user().'/'.$photo['id'].'.pmf';
		$file = fopen($fname, 'wb');
		$err = false;
		if ($file) {
			if (fwrite($file, "name=".$photo['name']."\n") !== FALSE) {
				if (fwrite($file, "desc=".$photo['description']."\n") !== FALSE) {
					if (fwrite($file, "pord=".$photo['p_order']."\n") !== FALSE) {
						if (fwrite($file, "albm=".wppa_get_album_name($photo['album'], 'raw')."\n") !== FALSE) {	
							if (fwrite($file, "lnku=".$photo['linkurl']."\n") !== FALSE) {
								if (fwrite($file, "lnkt=".$photo['linktitle']."\n") !== FALSE) {
								}
								else $err = true;
							}
							else $err = true;
						}
						else $err = true;
					} 
					else $err = true;
				}
				else $err = true;
			}
			else $err = true;
			if ($err) {
				wppa_error_message(sprintf(__('Cannot write to file %s.', 'wppa'),$fname));
				fclose($file);
				return false;
			}
			else {
				fclose($file);
				if ($wppa_zip) {
					$wppa_zip->addFile($fname, basename($fname));
				}
				$wppa_temp[$wppa_temp_idx] = $fname;
				$wppa_temp_idx++;
			}
		}
		else {
			wppa_error_message(__('Could not open photo output file.', 'wppa'));
			return false;
		}
	}
	else {
		wppa_error_message(__('Could not read photo data.'), 'wppa');
		return false;
	}
	return true;
}