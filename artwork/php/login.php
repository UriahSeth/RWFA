<?php
	if($t->get('username') == false && $t->get('password') == false) {
		$u = new gTemp('htm/login.htm');
		$t->set(array('main' => ($t->get('main') . $u->evaluate())));
	} else {
		$query = "SELECT name FROM users WHERE name='" . $t->get('username') . "' AND password='" . sha1($t->get('password')) . "'";
		$result = mysql_query($query);
		if(mysql_num_rows($result) == 1) {
			if(strtolower(mysql_result($result, 0)) == strtolower($t->get('username'))) {
				$_SESSION['username'] = mysql_result($result, 0);
				$_SESSION['logged'] = true;
				$t->set(array('password' => false));
				$t->set(array('username' => false));
			}
		} else {
			$t->set(array('password' => false));
		}
		
		$t->set(array('d' => 'main'));
	}
?>