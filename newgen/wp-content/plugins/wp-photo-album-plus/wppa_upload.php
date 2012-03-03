<?php 
/* wppa_upload.php
* Package: wp-photo-album-plus
*
* Contains all the upload/import pages and functions
* Version 3.0.3
*/

function wppa_page_upload() {
global $target;

	// upload images admin page

	// Check the existence of required directories
	if (!wppa_check_dirs()) return;

	// Check if an update message is required
	wppa_check_update();

    // sanitize system
	$user = wppa_get_user();
	wppa_cleanup_photos();
	wppa_sanitize_files($user);

	@set_time_limit(300); 

	// Do the upload if requested
	if (isset($_POST['wppa-upload'])) {
		wppa_check_admin_referer( '$wppa_nonce', WPPA_NONCE );
		wppa_upload_photos();
	} 
	if (isset($_POST['wppa-upload-zip'])) {
		wppa_check_admin_referer( '$wppa_nonce', WPPA_NONCE );
		$err = wppa_upload_zip();
		if (isset($_POST['wppa-go-import']) && $err == '0') { 
			wppa_ok_message(__('Connecting to your depot...', 'wppa'));
			update_option('wppa_import_source_'.$user, 'wp-content/wppa-depot/'.$user); ?>
			<script type="text/javascript">document.location = '<?php echo get_admin_url() ?>/admin.php?page=import_photos&zip=<?php echo $target ?>';</script>
		<?php }
	} 
	
	// sanitize system again
	wppa_cleanup_photos();
	wppa_sanitize_files($user);

	?>
	
	<div class="wrap">
		<?php $iconurl = get_bloginfo('wpurl') . '/wp-content/plugins/' . WPPA_PLUGIN_PATH . '/images/camera32.png'; ?>
		<div id="icon-camera" class="icon32" style="background: transparent url(<?php echo($iconurl); ?>) no-repeat">
			
		</div>
		<?php $iconurl = get_bloginfo('wpurl') . '/wp-content/plugins/' . WPPA_PLUGIN_PATH . '/images/arrow32.png'; ?>
		<div id="icon-arrow" class="icon32" style="background: transparent url(<?php echo($iconurl); ?>) no-repeat">
		</div>
		<?php $iconurl = get_bloginfo('wpurl') . '/wp-content/plugins/' . WPPA_PLUGIN_PATH . '/images/album32.png'; ?>
		<div id="icon-album" class="icon32" style="background: transparent url(<?php echo($iconurl); ?>) no-repeat">
			<br />
		</div>
		<h2><?php _e('Upload Photos', 'wppa'); ?></h2><br />

		<?php	
		$max_files = ini_get('max_file_uploads');
		$max_files_txt = $max_files;
		if ($max_files < '1') {
			$max_files_txt = __('unknown', 'wppa');
			$max_files = '15';
		}
		$max_size = ini_get('upload_max_filesize');
		$max_time = ini_get('max_input_time');	
		if ($max_time < '1') $max_time = __('unknown', 'wppa');
		// chek if albums exist before allowing upload
		if(wppa_has_albums()) { ?>
			<div style="border:1px solid #ccc; padding:10px; margin-bottom:10px; width: 600px; background-color:#fffbcc; border-color:#e6db55;">
			<?php echo(sprintf(__('<b>Notice:</b> your server allows you to upload <b>%s</b> files of maximum total <b>%s</b> bytes and allows <b>%s</b> seconds to complete.', 'wppa'), $max_files_txt, $max_size, $max_time)) ?>
			<?php _e('If your request exceeds these limitations, it will fail, probably without an errormessage.', 'wppa') ?>
			<?php _e('Additionally your hosting provider may have set other limitations on uploading files.', 'wppa') ?>
			</div>
			<div style="border:1px solid #ccc; padding:10px; margin-bottom:10px; width: 600px;">
				<h3 style="margin-top:0px;"><?php _e('Single Photos', 'wppa'); ?></h3><br />
				<?php //_e('You can select up to 15 photos one by one and upload them at once.', 'wppa'); ?>
				<form enctype="multipart/form-data" action="<?php echo(get_admin_url()) ?>/admin.php?page=upload_photos" method="post">
				<?php wppa_nonce_field('$wppa_nonce', WPPA_NONCE); ?>
					<input id="my_file_element" type="file" name="file_1" />
					<div id="files_list">
						<h3><?php _e('Selected Files:', 'wppa'); ?></h3>
						
					</div>
					<p>
						<label for="wppa-album"><?php _e('Album:', 'wppa'); ?> </label>
						<select name="wppa-album" id="wppa-album"><?php echo(wppa_album_select()); ?></select>
					</p>
					<input type="submit" class="button-primary" name="wppa-upload" value="<?php _e('Upload Single Photos', 'wppa') ?>" />					
				</form>
				<script type="text/javascript">
				<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->
					var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), <?php echo($max_files) ?> );
				<!-- Pass in the file element -->
					multi_selector.addElement( document.getElementById( 'my_file_element' ) );
				</script>
			</div>
			<?php if (PHP_VERSION_ID >= 50207) { ?>
				<div style="border:1px solid #ccc; padding:10px; width: 600px;">
					<h3 style="margin-top:0px;"><?php _e('Zipped Photos', 'wppa'); ?></h3><br />
					<?php _e('You can upload one zipfile at once. It will be placed in your personal wppa-depot.<br/>Once uploaded, use <b>Import Photos</b> to unzip the file and place the photos in any album.', 'wppa') ?>
					<form enctype="multipart/form-data" action="<?php echo(get_admin_url()) ?>/admin.php?page=upload_photos" method="post">
					<?php wppa_nonce_field('$wppa_nonce', WPPA_NONCE); ?>
						<input id="my_zipfile_element" type="file" name="file_zip" /><br/><br/>
						<input type="submit" class="button-primary" name="wppa-upload-zip" value="<?php _e('Upload Zipped Photos', 'wppa') ?>" />
						<input type="checkbox" name="wppa-go-import" checked="checked"><?php _e('After upload: Go to the <b>Import Photos</b> page.', 'wppa') ?></input>
					</form>
				</div>
			<?php }
			else { ?>
				<div style="border:1px solid #ccc; padding:10px; width: 600px;">
				<?php _e('<small>Ask your administrator to upgrade php to version 5.2.7 or later. This will enable you to upload zipped photos.</small>', 'wppa') ?>
				</div>
			<?php }
		}
	else { ?>
			<p><?php _e('No albums exist. You must', 'wppa'); ?> <a href="admin.php?page=<?php echo(WPPA_PLUGIN_PATH) ?>/wppa.php"><?php _e('create one', 'wppa'); ?></a> <?php _e('beofre you can upload your photos.', 'wppa'); ?></p>
<?php } ?>
	</div>
<?php
}

// import images admin page
function wppa_page_import() {

	// Check if a message is required
	wppa_check_update();

	// Check the existence of required directories
	if (!wppa_check_dirs()) return;
	
	// Sanitize system
    wppa_cleanup_photos('0');
	$user = wppa_get_user();
	$count = wppa_sanitize_files($user);
	if ($count) wppa_error_message($count.' '.__('illegal files deleted.', 'wppa'));
	
	// Do the dirty work
	if (isset($_GET['zip'])) {
	//	wppa_check_admin_referer( '$wppa_nonce', WPPA_NONCE );
		wppa_extract($_GET['zip'], true);
	}
	if (isset($_POST['wppa-import-set-source'])) {
		wppa_check_admin_referer( '$wppa_nonce', WPPA_NONCE );
		update_option('wppa_import_source_'.$user, $_POST['wppa-source']);
	}
	elseif (isset($_POST['wppa-import-submit'])) {
		wppa_check_admin_referer( '$wppa_nonce', WPPA_NONCE );
        if (isset($_POST['del-after-p'])) $delp = true; else $delp = false;
		if (isset($_POST['del-after-a'])) $dela = true; else $dela = false;	
		if (isset($_POST['del-after-z'])) $delz = true; else $delz = false;
		wppa_import_photos($delp, $dela, $delz);
	} 
	// Sanitize again
	$count = wppa_sanitize_files($user);
	if ($count) wppa_error_message($count.' '.__('illegal files deleted.', 'wppa'));
?>
	
	<div class="wrap">
		<?php $iconurl = get_bloginfo('wpurl') . '/wp-content/plugins/' . WPPA_PLUGIN_PATH . '/images/camera32.png'; ?>
		<div id="icon-camera" class="icon32" style="background: transparent url(<?php echo($iconurl); ?>) no-repeat"></div>
		<?php $iconurl = get_bloginfo('wpurl') . '/wp-content/plugins/' . WPPA_PLUGIN_PATH . '/images/arrow32.png'; ?>
		<div id="icon-arrow" class="icon32" style="background: transparent url(<?php echo($iconurl); ?>) no-repeat"></div>
		<?php $iconurl = get_bloginfo('wpurl') . '/wp-content/plugins/' . WPPA_PLUGIN_PATH . '/images/album32.png'; ?>
		<div id="icon-album" class="icon32" style="background: transparent url(<?php echo($iconurl); ?>) no-repeat"><br /></div>
		
		<h2><?php _e('Import Photos', 'wppa'); ?></h2><br />
<?php		
		// Get this users current source directory setting
		$source = get_option('wppa_import_source_'.$user, 'wp-content/wppa-depot/'.$user);

		$depot = ABSPATH . $source;	// Filesystem
		$depoturl = get_bloginfo('url').'/'.$source;	// url

		// See what's in there
		$paths = $depot.'/*.*';
		$files = glob($paths);
		$zipcount = wppa_get_zipcount($files);
		$albumcount = wppa_get_albumcount($files);
		$photocount = wppa_get_photocount($files);
		$is_depot = ($source == 'wp-content/wppa-depot/'.$user); ?>
		
		<form action="<?php echo(get_admin_url()) ?>/admin.php?page=import_photos" method="post">
		<?php wppa_nonce_field('$wppa_nonce', WPPA_NONCE); ?>
		<?php _e('Import photos from:', 'wppa'); ?>
			<select name="wppa-source">
				<option value="wp-content/wppa-depot/<?php echo($user) ?>" <?php if ($is_depot) echo('selected="selected"') ?>><?php _e('Your depot', 'wppa') ?></option>
				<?php $uplpat = get_option('upload_path', 'wp-content/uploads');
						if ($uplpat == '') $uplpat = 'wp-content/uploads'; ?>
				<?php wppa_walktree($uplpat, $source) ?>	
			</select>
			<input type="submit" class="button-secundary" name="wppa-import-set-source" value="<?php _e('Set source directory', 'wppa'); ?>" />
		</form>
<?php
		
		// chek if albums exist or will be made before allowing upload
		if(wppa_has_albums() || $albumcount > '0' || $zipcount >'0') { 
	
		if ($photocount > '0' || $albumcount > '0' || $zipcount >'0') { ?>
		
			<form action="<?php echo(get_admin_url()) ?>/admin.php?page=import_photos" method="post">
			<?php wppa_nonce_field('$wppa_nonce', WPPA_NONCE); 
			
			if (PHP_VERSION_ID >= 50207 && $zipcount > '0') { ?>		
			<p>
				<?php _e('There are', 'wppa'); echo(' '.$zipcount.' '); _e('zipfiles in the depot.', 'wppa') ?><br/>
				<input type="checkbox" name="del-after-z" checked="checked" />&nbsp;&nbsp;<?php _e('Delete after successful extraction.', 'wppa'); ?>
			</p>
			<table class="form-table albumtable">
				<tr>
					<?php
					$ct = 0;
					$idx = '0';
					foreach ($files as $file) {
			
						$ext = strtolower(substr(strrchr($file, "."), 1));
						if ($ext == 'zip') { ?>
							<td>
								<input type="checkbox" name="file-<?php echo($idx); ?>" checked="checked" />&nbsp;&nbsp;<?php echo(basename($file)); ?>
							</td>
							<?php if ($ct == 3) {
								echo('</tr><tr>'); 
								$ct = 0;
							}
							else {
								$ct++;
							}
						}
						$idx++;
					} ?>
				</tr>
			</table>
			<?php }
			if ($albumcount > '0') { ?>
			<p>
				<?php _e('There are', 'wppa'); echo(' '.$albumcount.' '); _e('albumdefinitions in the depot.', 'wppa') ?><br/>
				<input type="checkbox" name="del-after-a" checked="checked" />&nbsp;&nbsp;<?php _e('Delete after successful import, or if the album already exits.', 'wppa'); ?>
			</p>
			<table class="form-table albumtable">
				<tr>
					<?php
					$ct = 0;
					$idx = '0';
					foreach ($files as $file) {
						$ext = strtolower(substr(strrchr($file, "."), 1));
						if ($ext == 'amf') { ?>
							<td>
								<input type="checkbox" name="file-<?php echo($idx); ?>" checked="checked" />&nbsp;&nbsp;<?php echo(basename($file)); ?>&nbsp;<?php echo(stripslashes(wppa_get_meta_name($file, '('))) ?>
							</td>
							<?php if ($ct == 3) {
								echo('</tr><tr>'); 
								$ct = 0;
							}
							else {
								$ct++;
							}
						}
						$idx++;
					} ?>
				</tr>
			</table>
			<?php }
			if ($photocount > '0') { ?>
			<p>
				<?php _e('There are', 'wppa'); echo(' '.$photocount.' '); _e('photos in the depot.', 'wppa'); if (get_option('wppa_resize_on_upload', 'no') == 'yes') { echo(' '); _e('Photos will be downsized during import.', 'wppa'); } ?><br/>
				<?php if ($is_depot) { ?>
					<input type="checkbox" name="del-after-p" checked="checked" />&nbsp;&nbsp;<?php _e('Delete after successful import.', 'wppa'); ?>
				<?php } ?>
			</p>
			<p>
				<?php _e('Default album for import:', 'wppa'); ?><select name="wppa-album" id="wppa-album"><?php echo(wppa_album_select()); ?></select>
				<?php _e('Photos that have (<em>name</em>)[<em>album</em>] will be imported by that <em>name</em> in that <em>album</em>.', 'wppa') ?>
				</br>
			</p>
			<table class="form-table albumtable">
				<tr> 
					<?php
					$ct = 0;
					$idx = '0';
					foreach ($files as $file) {
						$ext = strtolower(substr(strrchr($file, "."), 1));
						$meta =	substr($file, 0, strlen($file)-3).'pmf';
						if ($ext == 'jpg' || $ext == 'png' || $ext == 'gif') { ?>
							<td>
								<input type="checkbox" name="file-<?php echo($idx); ?>" <?php if ($is_depot) echo('checked="checked"') ?> />&nbsp;&nbsp;<?php echo(basename($file)); ?>&nbsp;<?php echo(stripslashes(wppa_get_meta_name($meta, '('))) ?><?php echo(stripslashes(wppa_get_meta_album($meta, '['))) ?>
							</td>
							<?php if ($ct == 3) {
								echo('</tr><tr>'); 
								$ct = 0;
							}
							else {
								$ct++;
							}
						}
						$idx++;
					} ?>
				</tr>
			</table>
			<?php } ?>
			<p>
				<input type="submit" class="button-primary" name="wppa-import-submit" value="<?php _e('Import', 'wppa'); ?>" />
			</p>
			</form>
		<?php }
		else {
			if (PHP_VERSION_ID >= 50207) {
				wppa_ok_message(__('There are no archives, albums or photos in directory:', 'wppa').' '.$depoturl);
			}
			else {
				wppa_ok_message(__('There are no albums or photos in directory:', 'wppa').' '.$depoturl);
			}
		}
	}
	else { ?>
		<p><?php _e('No albums exist. You must', 'wppa'); ?> <a href="admin.php?page=<?php echo(WPPA_PLUGIN_PATH) ?>/wppa.php"><?php _e('create one', 'wppa'); ?></a> <?php _e('beofre you can upload your photos.', 'wppa'); ?></p>
<?php } ?>
	</div>
<?php
}

// Upload photos 
function wppa_upload_photos() {
	global $wpdb;
	global $warning_given;

	$warning_given = false;
	$uploaded_a_file = false;
	
	$count = '0';
	foreach ($_FILES as $file) {
		if ($file['tmp_name'] != '') {
			if (wppa_insert_photo($file['tmp_name'], $_POST['wppa-album'], $file['name'])) {
				$uploaded_a_file = true;
				$count++;
			}
			else {
				wppa_error_message(__('Error inserting photo', 'wppa') . ' ' . basename($file['tmp_name']) . '.');
				return;
			}
		}
	}
	
	if ($uploaded_a_file) { 
		wppa_update_message($count.' '.__('Photos Uploaded in album nr', 'wppa') . ' ' . $_POST['wppa-album']);
		wppa_set_last_album($_POST['wppa-album']);
    }
}

function wppa_upload_zip() {
global $target;

	$file = $_FILES['file_zip'];
	$name = $file['name'];
	$type = $file['type'];
	$error = $file['error'];
	$size = $file['size'];
	$temp = $file['tmp_name'];
	
	$user = wppa_get_user();
	
	$target = ABSPATH . 'wp-content/wppa-depot/'.wppa_get_user().'/'.$name;
	
	copy($temp, $target);
	
	if ($error == '0') wppa_ok_message(__('Zipfile', 'wppa').' '.$name.' '.__('sucessfully uploaded.', 'wppa'));
	else wppa_error_message(__('Error', 'wppa').' '.$error.' '.__('during upload.', 'wppa'));
	
	return $error;
}

function wppa_import_photos($delp = false, $dela = false, $delz = false) {
global $wpdb;
global $warning_given;

	$warning_given = false;
	
	// Get this users current source directory setting
	$user = wppa_get_user();
	$source = get_option('wppa_import_source_'.$user, 'wp-content/wppa-depot/'.$user);

	$depot = ABSPATH . $source;	// Filesystem
	$depoturl = get_bloginfo('url').'/'.$source;	// url

	// See what's in there
	$paths = $depot.'/*.*';
	$files = glob($paths);

	// First extract zips if our php version is ok
	$idx='0';
	$zcount = 0;
	if (PHP_VERSION_ID >= 50207) {
		foreach($files as $zipfile) {
			if (isset($_POST['file-'.$idx])) {
				$ext = strtolower(substr(strrchr($zipfile, "."), 1));
				
				if ($ext == 'zip') {
					$err = wppa_extract($zipfile, $delz);
					if ($err == '0') $zcount++;
				
/*					$zip = new ZipArchive;
					if ($zip->open($zipfile) === TRUE) {
						$zip->extractTo(ABSPATH . 'wp-content/wppa-depot/'.wppa_get_user().'/');
						$zip->close();
						wppa_ok_message(__('Zipfile', 'wppa').' '.$zipfile.' '.__('extracted.', 'wppa'));
						$zcount++;
						if ($delz) unlink($zipfile);
					} else {
						wppa_error_message(__('Failed to extract', 'wppa').' '.$zipfile);
					}
*/
				} // if zip
				
			} // if isset
			$idx++;
		} // foreach
	}
	
	// Now see if albums must be created
	$idx='0';
	$acount = 0;
	foreach($files as $album) {
		if (isset($_POST['file-'.$idx])) {
			$ext = strtolower(substr(strrchr($album, "."), 1));
			if ($ext == 'amf') {
				$name = '';
				$desc = '';
				$aord = '0';
				$parent = '0';
				$porder = '0';
				$owner = '';
				$handle = @fopen($album, "r");
				if ($handle) {
					$buffer = fgets($handle, 4096);
					while (!feof($handle)) {
						$tag = substr($buffer, 0, 5);
						$len = strlen($buffer) - 6;	// substract 5 for label and one for eol
						$data = substr($buffer, 5, $len);
						switch($tag) {
							case 'name=':
								$name = $data;
								break;
							case 'desc=':
								$desc = $data;
								break;
							case 'aord=':
								if (is_numeric($data)) $aord = $data;
								break;
							case 'prnt=':
								if ($data == __('--- none ---', 'wppa')) $parent = '0';
								elseif ($data == __('--- separate ---', 'wppa')) $parent = '-1';
								else {
									$prnt = wppa_get_album_id($data);
									if ($prnt != '') {
										$parent = $prnt;
									}
									else {
										$parent = '0';
										wppa_warning_message(__('Unknown parent album:', 'wppa').' '.$data.' '.__('--- none --- used.', 'wppa'));
									}
								}
								break;
							case 'pord=':
								if (is_numeric($data)) $porder = $data;
								break;
							case 'ownr=':
								$owner = $data;
								break;
						}
						$buffer = fgets($handle, 4096);
					} // while !foef
					fclose($handle);
					if (wppa_get_album_id($name) != '') {
						wppa_warning_message('Album already exists '.stripslashes($name));
						if ($dela) unlink($album);
					}
					else {
						$id = basename($album);
						$id = substr($id, 0, strpos($id, '.'));
						if (!wppa_is_id_free('album', $id)) $id = 0;
						$query = $wpdb->prepare('INSERT INTO `' . WPPA_ALBUMS . '` (`id`, `name`, `description`, `a_order`, `a_parent`, `p_order_by`, `main_photo`, `cover_linkpage`, `owner`) VALUES (%d, %s, %s, %d, %d, %d, %d, %d, %s)', $id, stripslashes($name), stripslashes($desc), $aord, $parent, $porder, 0, 0, $owner);
						$iret = $wpdb->query($query);

						if ($iret === FALSE) wppa_error_message(__('Could not create album.', 'wppa'));
						else {
							$id = wppa_get_album_id($name);
							wppa_set_last_album($id);
							wppa_ok_message(__('Album #', 'wppa') . ' ' . $id . ': '.stripslashes($name).' ' . __('Added.', 'wppa'));
							if ($dela) unlink($album);
							$acount++;
						} // album added
					} // album did not exist
				} // if handle (file open)
			} // if its an album
		} // if isset
		$idx++;
	} // foreach file
	
	// Now the photos
	$idx='0';
	$pcount = '0';
	if (isset($_POST['wppa-album'])) $album = $_POST['wppa-album']; else $album = '0';
	wppa_ok_message(__('Processing files, please wait...', 'wppa').' '.__('If the line of dots stops growing or you browser reports Ready, your server has given up. In that case: try again', 'wppa').' <a href="'.get_admin_url().'/admin.php?page=import_photos">'.__('here.', 'wppa').'</a>');
	foreach ($files as $file) {
		if (isset($_POST['file-'.$idx])) {
			$ext = strtolower(substr(strrchr($file, "."), 1));
			if ($ext == 'jpg' || $ext == 'png' || $ext == 'gif') {
				// See if a metafile exists
				$meta = substr($file, 0, strlen($file) - 3).'pmf';
				// find all data: name, desc, porder form metafile
				if (is_file($meta)) {
					$alb = wppa_get_album_id(wppa_get_meta_album($meta));
					$name = wppa_get_meta_name($meta);
					$desc = wppa_get_meta_desc($meta);
					$porder = wppa_get_meta_porder($meta);
					$linkurl = wppa_get_meta_linkurl($meta);
					$linktitle = wppa_get_meta_linktitle($meta);
				}
				else {
					$alb = $album;	// default album
					$name = '';		// default name
					$desc = '';		// default description
					$porder = '0';	// default p_order
					$linkurl = '';
					$linktitle = '';
				}
				// Insert the photo
				if (is_numeric($alb) && $alb != '0') {
					$id = basename($file);
					$id = substr($id, 0, strpos($id, '.'));
					if (!is_numeric($id) || !wppa_is_id_free('photo', $id)) $id = 0;
					if (wppa_insert_photo($file, $alb, stripslashes($name), stripslashes($desc), $porder, $id, stripslashes($linkurl), stripslashes($linktitle))) {
						$pcount++;
						if ($delp) {
							unlink($file);
							if (is_file($meta)) unlink($meta);
						}
					}
					else {
						wppa_error_message(__('Error inserting photo', 'wppa') . ' ' . basename($file) . '.');
					}
				}
				else {
					wppa_error_message(sprintf(__('Error inserting photo, album %1s does not exist for photo %2s. Either create the album or remove %3s.', 'wppa'), stripslashes(wppa_get_meta_album($meta)), basename($file), basename($meta)));
				} 
			}
		}
		$idx++;
	} // foreach $files
	wppa_ok_message(__('Done processing files.', 'wppa'));
	
	if ($pcount == '0' && $acount == '0' && $zcount == '0') {
		wppa_error_message(__('No files to import.', 'wppa'));
	}
	else {
		$msg = '';
		if ($zcount) $msg .= $zcount.' '.__('Zipfiles extracted.', 'wppa').' ';
		if ($acount) $msg .= $acount.' '.__('Albums created.', 'wppa').' ';
		if ($pcount) $msg .= $pcount.' '.__('Photos imported.', 'wppa').' '; 
		wppa_ok_message($msg); 
		wppa_set_last_album($album);
	}
}

function wppa_insert_photo ($file = '', $album = '', $name = '', $desc = '', $porder = '0', $id = '0', $linkurl = '', $linktitle = '') {
	global $wpdb;
	global $warning_given_small;
	global $warning_given_big;
	
	if ($name == '') $name = basename($file);
	if ($file != '' && $album != '' ) {
		$img_size = getimagesize($file);
		if ($img_size) { 
			if (!$warning_given_big && ($img_size['0'] > 1280 || $img_size['1'] > 1280)) {
				if (get_option('wppa_resize_on_upload', 'no') == 'yes') {
					wppa_ok_message(__('Although the photos are resized during the upload/import process, you may encounter \'Out of memory\'errors.', 'wppa') . '<br/>' . __('In that case: make sure you set the memory limit to 64M and make sure your hosting provider allows you the use of 64 Mb.', 'wppa'));
				}
				else {
					wppa_warning_message(__('WARNING: You are uploading very large photos, this may result in server problems and excessive download times for your website visitors.', 'wppa') . '<br/>' . __('Check the \'Resize on upload\' checkbox, and/or resize the photos before uploading. The recommended size is: not larger than 1024 x 768 pixels (up to approx. 250 kB).', 'wppa'));
				}
				$warning_given_big = true;
			}
			if (!$warning_given_small && ($img_size['0'] < wppa_get_minisize() && $img_size['1'] < wppa_get_minisize())) {
				wppa_warning_message(__('WARNING: You are uploading photos that are too small. Photos must be larger than the thumbnail size and larger than the coverphotosize.', 'wppa'));
				$warning_given_small = true;
			}
		}
		else {
			wppa_error_message(__('ERROR: Unable to retrieve immage size of', 'wppa').' '.$name.' '.__('Are you sure it is a photo?', 'wppa'));
			return false;
		}
		
//		$ext = substr(strrchr($name, "."), 1);
		// We now use mimetype, regardless of ext
		switch($img_size[2]) { 	// mime type
			case 1: $ext = 'gif'; break;
			case 2: $ext = 'jpg'; break;
			case 3: $ext = 'png'; break;
			default:
				wppa_error_message(__('Unsupported mime type encountered:', 'wppa').' '.$img_size[2].'.');
				return false;
		}
			
		$query = $wpdb->prepare('INSERT INTO `' . WPPA_PHOTOS . '` (`id`, `album`, `ext`, `name`, `p_order`, `description`, `mean_rating`, `linkurl`, `linktitle`) VALUES (%d, %d, %s, %s, %d, %s, \'\', %s, %s)', $id, $album, $ext, $name, $porder, $desc, $linkurl, $linktitle);
		if ($wpdb->query($query) === false) {
			wppa_error_message(__('Could not insert photo. query=', 'wppa').$query);
		}

		if ($id == '0') $image_id = $wpdb->get_var("SELECT LAST_INSERT_ID()");
		else $image_id = $id;
				
		$newimage = ABSPATH . 'wp-content/uploads/wppa/' . $image_id . '.' . $ext;
			
		if (get_option('wppa_resize_on_upload', 'no') == 'yes') {
			require_once('wppa_class_resize.php');
			
			if (wppa_is_wider($img_size[0], $img_size[1])) {
				$dir = 'W';
				$siz = get_option('wppa_fullsize', '640');
				$s = $img_size[0];
			}
			else {
				$dir = 'H';
				$siz = get_option('wppa_maxheight', get_option('wppa_fullsize', '640'));
				$s = $img_size[1];
			}

			if ($s > $siz) {	
				$objResize = new wppa_ImageResize($file, $newimage, $dir, $siz);
		$objResize->destroyImage($objResize->resOriginalImage);
		$objResize->destroyImage($objResize->resResizedImage);
			}
			else {
				copy($file, $newimage);
			}
		}
		else {
			copy($file, $newimage);
		}

		if (is_file ($newimage)) {
			$thumbsize = wppa_get_minisize();
			wppa_create_thumbnail($newimage, $thumbsize, '' );
		} 
		else {
			wppa_error_message(__('ERROR: Resized or copied image could not be created.', 'wppa'));
			return false;
		}
		echo('.');
		return true;
	}
	else {
		wppa_error_message(__('ERROR: Unknown file or album.', 'wppa'));
		return false;
	}
}

function wppa_get_zipcount($files) {
	$result = 0;
	if ($files) {
		foreach ($files as $file) {
			$ext = strtolower(substr(strrchr($file, "."), 1));
			if ($ext == 'zip') $result++;
		}
	}
	return $result;
}

function wppa_get_albumcount($files) {
	$result = 0;
	if ($files) {
		foreach ($files as $file) {
			$ext = strtolower(substr(strrchr($file, "."), 1));
			if ($ext == 'amf') $result++;
		}
	}
	return $result;
}

function wppa_get_photocount($files) {
	$result = 0;
	if ($files) {
		foreach ($files as $file) {
			$ext = strtolower(substr(strrchr($file, "."), 1));
			if ($ext == 'jpg' || $ext == 'png' || $ext == 'gif') $result++;
		}
	}
	return $result;
}

function wppa_get_meta_name($file, $opt = '') {
	return wppa_get_meta_data($file, 'name', $opt);
}
function wppa_get_meta_album($file, $opt = '') {
	return wppa_get_meta_data($file, 'albm', $opt);
}
function wppa_get_meta_desc($file, $opt = '') {
	return wppa_get_meta_data($file, 'desc', $opt);
}
function wppa_get_meta_porder($file, $opt = '') {
	return wppa_get_meta_data($file, 'pord', $opt);
}
function wppa_get_meta_linkurl($file, $opt = '') {
	return wppa_get_meta_data($file, 'lnku', $opt);
}
function wppa_get_meta_linktitle($file, $opt = '') {
	return wppa_get_meta_data($file, 'lnkt', $opt);
}

function wppa_get_meta_data($file, $item, $opt) {
	$result = '';
	$opt2 = '';
	if ($opt == '(') $opt2 = ')';
	if ($opt == '{') $opt2 = '}';
	if ($opt == '[') $opt2 = ']';
	if (is_file($file)) {
		$handle = @fopen($file, "r");
		if ($handle) {
			while (($buffer = fgets($handle, 4096)) !== false) {
				if (substr($buffer, 0, 5) == $item.'=') {
					if ($opt == '') $result = substr($buffer, 5, strlen($buffer)-6);
					else $result = $opt.wppa_qtrans(substr($buffer, 5, strlen($buffer)-6)).$opt2;		// Translate for display purposes only
				}
			}
			if (!feof($handle)) {
				_e('Error: unexpected fgets() fail in wppa_get_meta_data().', 'wppa');
			}
			fclose($handle);
		}
	}
	return $result;
}

// Remove photo entries that have no fullsize image or thumbnail
function wppa_cleanup_photos($alb = '') {
	global $wpdb;
	if ($alb == '') $alb = wppa_get_last_album();
	if (!is_numeric($alb)) return;

	$no_photos = '';
//	if ($alb == '0') wppa_ok_message(__('Checking database, please wait...', 'wppa'));
	$delcount = 0;
	if ($alb == '0') $entries = $wpdb->get_results('SELECT id, ext, name FROM '.WPPA_PHOTOS, ARRAY_A);
	else $entries = $wpdb->get_results('SELECT id, ext, name FROM '.WPPA_PHOTOS.' WHERE album = '.$alb, ARRAY_A);
	if ($entries) {
		foreach ($entries as $entry) {
			$thumbpath = ABSPATH.'wp-content/uploads/wppa/thumbs/'.$entry['id'].'.'.$entry['ext'];
			$imagepath = ABSPATH.'wp-content/uploads/wppa/'.$entry['id'].'.'.$entry['ext'];
			if (!is_file($thumbpath)) {	// No thumb: delete fullimage
				if (is_file($imagepath)) unlink($imagepath);
				$no_photos .= ' '.$entry['name'];
			}
			if (!is_file($imagepath)) { // No fullimage: delete db entry
				if ($wpdb->query($wpdb->prepare('DELETE FROM `'.WPPA_PHOTOS.'` WHERE `id` = %d LIMIT 1', $entry['id']))) {
					$delcount++;
				}
			}
		}
	}
	// Now fix missing exts for upload bug in 2.3.0
	$fixcount = 0;
	$entries = $wpdb->get_results('SELECT id, ext, name FROM '.WPPA_PHOTOS.' WHERE ext = ""', ARRAY_A);
	if ($entries) {
		wppa_ok_message(__('Trying to fix '.count($entries).' entries with missing file extension, Please wait.', 'wppa'));
		foreach ($entries as $entry) {
			$tp = ABSPATH.'wp-content/uploads/wppa/'.$entry['id'].'.';
			// Try the name
			$ext = substr(strrchr($entry['name'], "."), 1);
			if (!($ext == 'jpg' || $ext == 'JPG' || $ext == 'png' || $ext == 'PNG' || $ext == 'gif' || $ext == 'GIF')) {
				$ext = '';
			}
			if ($ext == '' && is_file($tp)) {
			// Try the type from the file
				$img = getimagesize($tp);
				if ($img[2] == 1) $ext = 'gif';
				if ($img[2] == 2) $ext = 'jpg';
				if ($img[2] == 3) $ext = 'png';
			}
			if ($ext == 'jpg' || $ext == 'JPG' || $ext == 'png' || $ext == 'PNG' || $ext == 'gif' || $ext == 'GIF') {
				
				if ($wpdb->query('UPDATE '.WPPA_PHOTOS.' SET ext = "'.$ext.'" WHERE id = '.$entry['id'])) {
					$oldimg = ABSPATH.'wp-content/uploads/wppa/'.$entry['id'].'.';
					$newimg = ABSPATH.'wp-content/uploads/wppa/'.$entry['id'].'.'.$ext;
					if (is_file($oldimg)) {
						copy($oldimg, $newimg);
						unlink($oldimg);
					}
					$oldimg = ABSPATH.'wp-content/uploads/wppa/thumbs/'.$entry['id'].'.';
					$newimg = ABSPATH.'wp-content/uploads/wppa/thumbs/'.$entry['id'].'.'.$ext;
					if (is_file($oldimg)) {
						copy($oldimg, $newimg);
						unlink($oldimg);
					}
					$fixcount++;
					wppa_ok_message(__('Fixed extension for ', 'wppa').$entry['name']);
				}
				else {
					wppa_error_message(__('Unable to fix extension for ', 'wppa').$entry['name']);
				}
			}
			else {
				wppa_error_message(__('Unknown extension for photo ', 'wppa').$entry['name'].'. '.__('Please change the name to something with the proper extension and try again!', 'wppa'));
			}
		}	
	}
	
	// Now fix orphan photos
	$orphcount = 0;
	$entries = $wpdb->get_results('SELECT id FROM '.WPPA_PHOTOS.' WHERE album = 0', ARRAY_A);
	if ($entries) {
		$album = wppa_get_album_id(__('Orphan Photos', 'wppa'));
		if ($album == '') {
			$query = $wpdb->prepare('INSERT INTO `' . WPPA_ALBUMS . '` (`id`, `name`, `description`, `a_order`, `a_parent`, `p_order_by`, `main_photo`, `cover_linkpage`, `owner`) VALUES (0, %s, %s, %d, %d, %d, %d, %d, %s)', __('Orphan Photos', 'wppa'), $desc, 0, 0, 0, 0, 0, 'admin');
			$iret = $wpdb->query($query);
			if ($iret === false) {
				wppa_error_message('Could not create album: Orphan Photos', 'wppa');
			}
			else {
				wppa_ok_message('Album: Orphan Photos created.', 'wppa');
			}
			$album = wppa_get_album_id(__('Orphan Photos', 'wppa')); // retry
		}
		if ($album) {
			$orphcount = $wpdb->query('UPDATE '.WPPA_PHOTOS.' SET album = '.$album.' WHERE album < 1');
		}
		else {
			wppa_error_message(__('Could not recover orphanized photos.', 'wppa'));
		}
	}

	// End fix
	if ($orphcount > 0){
		wppa_ok_message(__('Database fixed.', 'wppa').' '.$orphcount.' '.__('orphanized photos recovered.', 'wppa'));
	}
	if ($delcount > 0){
		wppa_ok_message(__('Database fixed.', 'wppa').' '.$delcount.' '.__('invalid entries removed:', 'wppa').$no_photos);
	}
	if ($fixcount > 0) {
		wppa_ok_message(__('Database fixed.', 'wppa').' '.$fixcount.' '.__('missing file extensions recovered.', 'wppa'));
	}
		if ($alb == '0' && $delcount == 0 && $fixcount == 0) {
//		wppa_ok_message(__('Done. No errors found. Have a nice upload!', 'wppa'));
	}
}


// Check if the required directories exist, if not, try to create them and report it
function wppa_check_dirs() {

	@define('WP_DEBUG', true);	

	// check if uploads dir exists
	$dir = ABSPATH . 'wp-content/uploads';
	if (!is_dir($dir)) {
		@mkdir($dir);
		if (!is_dir($dir)) {
			wppa_error_message(__('The uploads directory does not exist, please do a regular WP upload first.', 'wppa'));
			return false;
		}
		else {
//			@chmod($dir, 0755);
			wppa_ok_message(__('Successfully created uploads directory.', 'wppa'));
		}
	}	

	// check if wppa dir exists
	$dir = ABSPATH . 'wp-content/uploads/wppa';
	if (!is_dir($dir)) {
		@mkdir($dir);
		if (!is_dir($dir)) {
			wppa_error_message(__('Could not create the wppa directory.', 'wppa').wppa_credirmsg($dir));
			return false;
		}
		else {
//			@chmod($dir, 0755);
			wppa_ok_message(__('Successfully created wppa directory.', 'wppa'));
		}
	}
	
	// check if thumbs dir exists 
	$dir = ABSPATH . 'wp-content/uploads/wppa/thumbs';
	if (!is_dir($dir)) {
		@mkdir($dir);
		if (!is_dir($dir)) {
			wppa_error_message(__('Could not create the wppa thumbs directory.', 'wppa').wppa_credirmsg($dir));
			return false;
		}
		else {
//			@chmod($dir, 0755);
			wppa_ok_message(__('Successfully created wppa thumbs directory.', 'wppa'));
		}
	}
	
	// check if depot dir exists
	$dir = ABSPATH . 'wp-content/wppa-depot';
	if (!is_dir($dir)) {
		@mkdir($dir);
		if (!is_dir($dir)) {
			wppa_error_message(__('Unable to create depot directory', 'wppa').wppa_credirmsg($dir));
			return false;
		}
		else {
//			@chmod($dir, 0755);
			wppa_ok_message(__('Successfully created wppa master depot directory.', 'wppa'));
		}
	}
	
	// check if users depot dir exists
	$dir = ABSPATH . 'wp-content/wppa-depot/'.wppa_get_user();
	if (!is_dir($dir)) {
		@mkdir($dir);
		if (!is_dir($dir)) {
			wppa_error_message(__('Unable to create user depot directory.', 'wppa').wppa_credirmsg($dir));
			return false;
		}
		else {
//			@chmod($depot, 0755);
			wppa_ok_message(__('Successfully created wppa user depot directory.', 'wppa'));
		}
	}
	
	return true;
}
function wppa_credirmsg($dir) {
	$msg = ' '.sprintf(__('Ask your administrator to give you more rights, try CHMOD from table VII item 1 of the Photo Albums -> Settings admin page or create <b>%s</b> manually using an FTP program.', 'wppa'), $dir);
	return $msg;
}

function wppa_walktree($relroot, $source) {

	if ($relroot == $source) $sel=' selected="selected"'; else $sel = ' ';
	echo('<option value="'.$relroot.'"'.$sel.'>'.$relroot.'</option>');
	
	if ($handle = opendir(ABSPATH.$relroot)) {
		while (false !== ($file = readdir($handle))) {
			if (($file) != "." && ($file) != ".." && ($file) != "wppa") {
				$newroot = $relroot.'/'.$file;
				if (is_dir(ABSPATH.$newroot)) {	
					wppa_walktree($newroot, $source);
				}
			}
		}
		closedir($handle);
	}
}

function wppa_is_id_free($type, $id) {
global $wpdb;
	if (!is_numeric($id)) return false;
	if ($id == '0') return false;
	
	$table = '';
	if ($type == 'album') $table = WPPA_ALBUMS;
	if ($type == 'photo') $table = WPPA_PHOTOS;
	if ($table == '') {
		echo('Unexpected error in wppa_is_id_free()');
		return false;
	}
	$res = $wpdb->get_row('SELECT * FROM '.$table.' WHERE id = '.$id, 'ARRAY_A');
	if ($res) return false;
	return true;
}

function wppa_sanitize_files($user) {

	// Get this users depot directory
	$depot = ABSPATH.'wp-content/wppa-depot/'.$user;
	// See what's in there
	$paths = $depot.'/*.*';
	$files = glob($paths);
	$allowed_types = array('zip', 'jpg', 'png', 'gif', 'amf', 'pmf', 'bak');

	$count = '0';
	if ($files) foreach ($files as $file) {
		if (is_file($file)) {
			$ext = strtolower(substr(strrchr($file, "."), 1));
			if (!in_array($ext, $allowed_types)) {
				unlink($file);
				wppa_error_message(sprintf(__('File %s is of an unsupported filetype and has been removed.', 'wppa'), basename($file)));
//echo($file); echo('<br>');
				$count++;
			}
		}
	}
	return $count;
}

function wppa_extract($path, $delz) {
// There are two reasons that we do not allow the directory structure from the zipfile to be restored.
// 1. we may have no create dir access rights.
// 2. we can not reach the pictures as we only glob the users depot and not lower.
// We extract all files to the users depot. 
// The illegal files will be deleted there by the wppa_sanitize_files routine, 
// so there is no chance a depot/subdir/destroy.php or the like will get a chance to be created.
// dus...

	$err = '0';
	$ext = strtolower(substr(strrchr($path, "."), 1));
	if ($ext == 'zip') {
		$zip = new ZipArchive;
		if ($zip->open($path) === true) {
// 			this for-loop replaces the $zip->extractTo() line
		    for($i = 0; $i < $zip->numFiles; $i++) {
				$filename = $zip->getNameIndex($i);
				$fileinfo = pathinfo($filename);
				copy("zip://".$path."#".$filename, ABSPATH."wp-content/wppa-depot/".wppa_get_user()."/".$fileinfo['basename']);
			}                  
//			$zip->extractTo(ABSPATH . 'wp-content/wppa-depot/'.wppa_get_user().'/');
			$zip->close();
			wppa_ok_message(__('Zipfile', 'wppa').' '.basename($path).' '.__('extracted.', 'wppa'));
			if ($delz) unlink($path);
		} else {
			wppa_error_message(__('Failed to extract', 'wppa').' '.$path);
			$err = '1';
		}
	}
	else $err = '2';
	
	return $err;
}

/*
$path = 'zipfile.zip'

$zip = new ZipArchive;
if ($zip->open($path) === true) {
    for($i = 0; $i < $zip->numFiles; $i++) {
        $filename = $zip->getNameIndex($i);
        $fileinfo = pathinfo($filename);
        copy("zip://".$path."#".$filename, "/your/new/destination/".$fileinfo['basename']);
    }                  
    $zip->close();                  
}
*/
