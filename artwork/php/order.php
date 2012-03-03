<?php
if(isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
	$t->whitelist('dir', 'alpha', 'hash', 'alnum');

	if($t->get('dir') == 'down'){
		$query = "SELECT c.ID, c.ord FROM common as c LEFT JOIN common as d ON(d.parent='" . $t->get('hash') . "')  WHERE c.ord <= d.ord ORDER BY c.ord DESC LIMIT 2";
	} else if($t->get('dir') == 'up') {
		$query = "SELECT c.ID, c.ord FROM common as c LEFT JOIN common as d ON(d.parent='" . $t->get('hash') . "')  WHERE c.ord >= d.ord ORDER BY c.ord ASC LIMIT 2";
	} else {
		$t->set(array('error' => 'Improper Direction'));
	}

	if(!$t->get('error')){
		$result = mysql_query($query);
		if(mysql_num_rows($result) == 2){
			$top = mysql_fetch_array($result);
			$bottom = mysql_fetch_array($result);
			mysql_query("START TRANSACTION");
			$query = "UPDATE common SET ord=-1 WHERE ID=" . $top['ID'];
			$result = mysql_query($query);
			if(!mysql_error()){
				$query = "UPDATE common SET ord=" . $top['ord'] . " WHERE ID=" . $bottom['ID'];
				$result = mysql_query($query);
				if(!mysql_error()){
					$query = "UPDATE common SET ord=" . $bottom['ord'] . " WHERE ID=" . $top['ID'];
					$result = mysql_query($query);
					if(!mysql_error()){
						mysql_query("COMMIT");
					} else {
						$t->set(array('error' => 'Database Error'));
						mysql_query("ROLLBACK");
					}
				} else {
					$t->set(array('error' => 'Database Error'));
					mysql_query("ROLLBACK");
				}
			} else {
				$t->set(array('error' => 'Database Error'));
				mysql_query("ROLLBACK");
			}
		}
	}
}
$t->set(array('d' => 'main'));
?>