<?php
	$u = new gTemp('htm/image.htm');
	$u->whitelist('hash', 'alnum');
	$query = "SELECT i.hashID, c.name, DATE_FORMAT(c.date, '%b %e, %Y') as date, i.parent AS parID, c.dimensions, c.optional, c.sold FROM images as i LEFT JOIN common as c ON (i.parent = c.parent) WHERE i.parent = '" . $u->get('hash') . "' AND i.type = 2";
	$u->set(mysql_fetch_array(mysql_query($query)));
	$t->set(array('main' => ($t->get('main') . $u->evaluate())));
?>