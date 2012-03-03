<?php
if(isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
	$u = new gTemp('htm/admin.htm');

	$u->whitelist('action', 'alpha', 'hash', 'alnum', 'username', 'alnum', 'password', 'print', 'confirm', 'print');

	$orig_action = "";
	if(isset($_FILES['datafile']) && $_FILES['datafile']['error'] == 0 && $u->get('action') == 'upit') {
		$orig_action = 'upit';
		$size = getimagesize($_FILES['datafile']['tmp_name']);
		if(is_array($size) && ($size[0] == 1024 || $size[1] == 1024)) {
			$filename = md5_file($_FILES['datafile']['tmp_name']);
			if(!file_exists("img/$filename.jpg")) {
				if(move_uploaded_file($_FILES['datafile']['tmp_name'], "img/$filename.jpg")) {
					copy("img/$filename.jpg", "img/$filename-2.jpg");
					copy("img/$filename.jpg", "img/$filename-3.jpg");
					if($size[0] >= $size[1]) {
						$geo = "640x" . round(640*$size[1]/$size[0]);
					} else {
						$geo = round(640*$size[1]/$size[0]) . "x640";
					}
					shell_exec('mogrify -resize ' . $geo . " img/$filename-2.jpg");
					$filename2 = md5_file("img/$filename-2.jpg");
					shell_exec("mv img/$filename-2.jpg img/$filename2.jpg");
					if($size[0] >= $size[1]) {
						$geo = "200x" . round(200*$size[1]/$size[0]);
					} else {
						$geo = round(200*$size[1]/$size[0]) . "x200";
					}
					shell_exec('mogrify -resize ' . $geo . " img/$filename-3.jpg");
					$filename3 = md5_file("img/$filename-3.jpg");
					shell_exec("mv img/$filename-3.jpg img/$filename3.jpg");

					$exif = @exif_read_data('img/' . $filename . '.jpg');
					
					$u->set(array('file' => true, 'hash1' => $filename, 'hash2' => $filename2, 'hash3' => $filename3, 'date' => strftime("%F %T", time()), 'filename' => substr($_FILES['datafile']['name'], 0, strlen($_FILES['datafile']['name'])-4), 'action' => 'postit'));
				} else {
					$t->set(array('error' => 'Error moving file!'));
				}
			} else {
				$t->set(array('error' => 'File already exists!'));
			}
		} else {
			$t->set(array('error' => 'File size must be 1024px on the long edge!'));
		}
	}

	if($u->get('action') == 'edit' && $orig_action == "") {
		$orig_action = 'edit';
		$u->whitelist('hash', 'alnum');
		$query = "SELECT c.name AS filename, c.date, c.dimensions, c.optional, c.sold, i.hashid as hash2, c.parent as hash1 FROM common AS c LEFT JOIN images AS i ON ( c.parent = i.parent ) WHERE c.parent = '" . $u->get('hash') . "' AND i.type =2";
		$result = mysql_query($query);
		$u->set(array('file' => true, 'action' => 'editit'));
		$u->set(mysql_fetch_array($result));
		if($u->get('sold') == 1){
			$u->set(array('checked' => 'CHECKED'));
		}
	}

	if($u->get('action') == 'editit' && $orig_action == "") {
		$orig_action = 'editit';
		$u->whitelist('filename', 'print', 'hash1', 'alnum', 'date', 'print', 'cancel', 'alpha', 'dimensions', 'print', 'optional', 'print', 'sold', 'alnum');
		$t->set(array('error' => false));
		if($u->get('hash1')) {
			$out_array = array();
			preg_match_all('/([0-9]+)/', $u->get('dimensions'), $out_array);
			$u->set(array('dimensions' => $out_array[1][0] . '&quot; x ' . $out_array[1][1] . '&quot;'));
			$query = "UPDATE common SET name='" . $u->get('filename') . "', date='" . $u->get('date') . "', dimensions='" . $u->get('dimensions') . "', optional='" . $u->get('optional') . "', sold=" . ($u->get('sold')===false?0:1) . " WHERE parent='" . $u->get('hash1') . "' LIMIT 1";
			mysql_query("START TRANSACTION");
			mysql_query($query);
			if(mysql_error()) {
				mysql_query("ROLLBACK");
				$t->set(array('error' => "Query failed!"));
				$u->set(array('action' => 'edit'));
				$_REQUEST['hash'] = $u->get('hash1');
			} else {
				mysql_query("COMMIT");
				$t->set(array('d' => 'image'));
				$_REQUEST['hash'] = $u->get('hash1');
			}
		} else {
			$t->set(array('error' => 'Unknown image!'));
		}
	}

	if($u->get('action') == 'postit' && $orig_action == "") {
		$orig_action = 'postit';
		$u->whitelist('filename', 'print', 'hash1', 'alnum', 'hash2', 'alnum', 'hash3', 'alnum', 'date', 'print', 'cancel', 'alpha', 'dimensions', 'print', 'optional', 'print', 'sold', 'alnum');
			if($u->get('hash1') && $u->get('hash2') && $u->get('hash3')) {
				if($u->get('cancel') == "Cancel") {
					shell_exec("rm img/" . $u->get('hash1') . ".jpg");
					shell_exec("rm img/" . $u->get('hash2') . ".jpg");
					shell_exec("rm img/" . $u->get('hash3') . ".jpg");
					$t->set(array('error' => 'Uploaded image deleted!'));
				} else {
					$query = "SELECT ord as ordnum FROM common WHERE 1 ORDER BY ord DESC LIMIT 1";
					$result = mysql_query($query);
					$ordnum = mysql_result($result, 0, "ordnum")+1;
					mysql_query("START TRANSACTION");
					$worked = false;
					$query = "INSERT INTO images (hashID, parent, type) VALUES ('" . $u->get('hash1') . "', NULL, 1), ('" . $u->get('hash2') . "', '" . $u->get('hash1') . "', 2), ('" . $u->get('hash3') . "', '" . $u->get('hash1') . "', 3)";
					$result = mysql_query($query);
					if(mysql_affected_rows() == 3) {
						$out_array = array();
						preg_match_all('/([0-9]+)/', $u->get('dimensions'), $out_array);
						$u->set(array('dimensions' => $out_array[1][0] . '&quot; x ' . $out_array[1][1] . '&quot;'));
						$query = "INSERT INTO common (parent, name, date, dimensions, optional, ord, sold) VALUES ('" . $u->get('hash1') . "', '" . $u->get('filename') . "', '" . ($u->get('date')?$u->get('date'):strftime("%F %T")) . "', '" . $u->get('dimensions') . "', '" . $u->get('optional') . "', '" . $ordnum . "', " . ($u->get('sold')===false?0:1) . ")";
						$result = mysql_query($query);
						if(mysql_error()) {
							$t->set(array('error' => 'Error posting to database.'));
							mysql_query("ROLLBACK");
						} else {
							$t->set(array('error' => $u->get('filename') . ' posted.'));
							mysql_query("COMMIT");
							$worked = true;
						}
					} else {
						$t->set(array('error' => 'Error posting to database.'));
						mysql_query("ROLLBACK");
					}
				}
				if($worked == false) {
					shell_exec("rm img/" . $u->get('hash1') . ".jpg");
					shell_exec("rm img/" . $u->get('hash2') . ".jpg");
					shell_exec("rm img/" . $u->get('hash3') . ".jpg");
				}
			} else {
				$t->set(array('error' => 'Improper hash values!'));
			}
	}

	if($u->get('action') == 'delete' && $orig_action == "" && $u->get('hash') != "") {
		$orig_action = "delete";
		$u = new gTemp('htm/delete.htm');
		$u->whitelist('hash', 'alnum');
		$query = "SELECT c.name, i.hashID FROM common as c LEFT JOIN images as i ON (c.parent=i.parent AND i.type=3) WHERE c.parent='" . $u->get('hash') . "'";
		$gotten = mysql_fetch_array(mysql_query($query));
		$u->set($gotten);
		$t->set(array('main' => ($t->get('main') . $u->evaluate()), 'admin' => true));
	}

	if($u->get('action') == 'useradd' && $u->get('username') != "" && $u->get('password') != "" && $u->get('confirm') != "") {
		if($u->get('password') == $u->get('confirm')) {
			mysql_query("START TRANSACTION");
			$query = "INSERT INTO users (name, password) VALUES ('" . $u->get('username') . "', '" . sha1($u->get('password')) . "')";
			$result = mysql_query($query);
			if(mysql_error()) {
				$t->set(array('error' => 'Error Creating User.<br/>\n' . mysql_error()));
				mysql_query("ROLLBACK");
			} else {
				$t->set(array('error' => 'User ' . $u->get('username') . ' created!'));
				mysql_query("COMMIT");
			}
		} else {
			$t->set(array('error' => 'Passwords do not match'));
		}
	} else {
		if($u->get('action') == 'useradd')
			$t->set(array('error' => 'Must fill in all user fields.'));
	}


	if($u->get('action') == 'userdel' && $u->get('username') != "" && $u->get('username') != $_SESSION['username']) {
		mysql_query("START TRANSACTION");
		$query = "DELETE FROM users WHERE name = '" . $u->get('username') . "'";
		mysql_query($query);
		if(mysql_error()) {
			$t->set(array('error' => 'Error Deleting User.<br/>\n' . mysql_error()));
			mysql_query("ROLLBACK");
		} else {
			$t->set(array('error' => 'User ' . $u->get('username') . ' deleted!'));
			mysql_query("COMMIT");
		}	
	} else {
		//var_dump($_REQUEST);
		//$t->set(array('error' => 'Ineligible User!'));
	}

	if($u->get('action') == 'confdel' && $orig_action == "" && $u->get('hash') != "") {
		$orig_action="confdel";
		$u->whitelist('buttons', 'alpha');
		if($u->get('buttons') == "Confirm"){
			$query = "SELECT i.hashID, j.hashID as parent FROM images as i LEFT JOIN images as j ON (i.parent=j.hashID) WHERE j.hashID='" . $u->get('hash') . "'";
			$result = mysql_query($query);
			$gotten = mysql_fetch_array($result);
			$hash1 = $gotten['parent'];
			$hash2 = $gotten['hashID'];
			$gotten = mysql_fetch_array($result);
			$hash3 = $gotten['hashID'];
			mysql_query("START TRANSACTION");
			$query = "DELETE i, c FROM images as i LEFT JOIN common as c ON (i.hashID=c.parent) WHERE i.parent='" . $u->get('hash') . "' OR i.hashID='" . $u->get('hash') . "' OR c.parent='" . $u->get('hash') . "'";
			mysql_query($query);
			if(!mysql_error()){
				shell_exec("rm img/$hash1.jpg");
				shell_exec("rm img/$hash2.jpg");
				shell_exec("rm img/$hash3.jpg");
				mysql_query("COMMIT");
				$t->set(array('d' => 'main'));
			} else {
				mysql_query("ROLLBACK");
				$t->set(array('error' => 'Error in deletion.'));
				$t->set(array('d' => 'image'));
			}
		} else {
			$t->set(array('d' => 'image'));
		}
	}

	if($orig_action != "confdel" && $orig_action != "delete") {
		$query = "SELECT name FROM users WHERE 1";
		$result = mysql_query($query);
		while($gotten = mysql_fetch_array($result)) {
			if($gotten['name'] == $_SESSION['username'])
				$temp[] = array_merge($gotten, array('loggedin' => true));
			else
				$temp[] = $gotten;
		}
		$u->set(array('users' => $temp));
		if($t->get('d') == 'admin') {
			$t->set(array('main' => ($t->get('main') . $u->evaluate()), 'admin' => true));
		}
	}
} else {
	$t->set(array('d' => 'main'));
}
?>
