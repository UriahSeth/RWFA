<?php
//$test = fopen("test.txt", "a");
if(isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
	$t->whitelist('hash', 'alnum');
	if($t->get('hash')) {
		mysql_query("START TRANSACTION");
		$query = "UPDATE common SET sold = NOT sold WHERE parent='" . $t->get('hash') . "'";
		mysql_query($query);
		//fwrite($test, $query . "\n");
		if(mysql_error()) {
			mysql_query("ROLLBACK");
			//fwrite($test, "Fail: " . mysql_error() . "\n");
		} else {
			mysql_query("COMMIT");
			//fwrite($test, "Win!\n");
		}
	}
}
?>
