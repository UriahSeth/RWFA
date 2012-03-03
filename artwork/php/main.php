<?php
	$u = new gTemp('htm/images.htm');
	$u->set(array('pages' => false));
	$u->whitelist('on', 'digit');
	$on = $u->get('on')?$u->get('on'):0;
	$u->set(array('d' => $t->get('d')));
	$minmax = mysql_fetch_array(mysql_query("SELECT MAX(ord) as maxord, MIN(ord) as minord FROM `common` WHERE 1"));
	$query = "SELECT i.hashID, c.name, DATE_FORMAT(c.date, '%b %e, %Y') as date, i.parent AS parID, c.dimensions, c.ord, c.sold FROM images as i LEFT JOIN common as c ON (i.parent = c.parent) WHERE i.type=3 AND i.parent IN (SELECT i2.hashID FROM images as i2 WHERE i2.type=1 AND i2.parent IS NULL) ORDER BY c.ord DESC LIMIT " . ($on?$on*12:0) . ", 12";
	$result = mysql_query($query);
	if($result) {
		$rows = mysql_num_rows($result);
		if($rows > 0) {
			$x = 0;
			$imagerows = array();
			while($gotten = mysql_fetch_array($result)) {
				if($gotten['ord'] == $minmax['minord']){
					$gotten = array_merge($gotten, array('bottom' => true));
				} else if($gotten['ord'] == $minmax['maxord']) {
					$gotten = array_merge($gotten, array('top' => true));
				}
				if($gotten['sold'] == 1) {
					$gotten = array_merge($gotten, array('checked' => 'CHECKED'));
				}
				$imagerows[$x]['image'][] = $gotten;
				if(count($imagerows[$x]['image']) == 2) {
					$x++;
				}
			}
			if(count($imagerows[$x]['image'])%2){
				$imagerows[$x]['image'][] = array();
			}

			$query = "SELECT COUNT(i.hashID) as count FROM images as i LEFT JOIN common as c ON (i.parent = c.parent) WHERE i.type=3 AND i.parent IN (SELECT i2.hashID FROM images as i2 WHERE i2.type=1 AND i2.parent IS NULL)";
			$result = mysql_query($query);
			$pages = ceil(mysql_result($result, 0)/12);
			$u->set(array('pages' => array()));
			$temp = array();
			$temp[] = array('pagename' => '&lt;Prev', 'page' => $on-1<0?0:$on-1, 'selected' => $on-1<0?true:false);
			for($x = 0; $x < $pages; $x++) {
				$temp[] = array('pagename' => $x+1, 'page' => $x, 'selected' => $on==$x?true:false);
			}
			$temp[] = array('pagename' => 'Next&gt;', 'page' => $on+1>$pages?0:$on+1, 'selected' => $on+1>=$pages?true:false);
			$u->set(array('pages' => $temp));
			$u->set(array('imagerows' => $imagerows));
			$t->set(array('main' => ($t->get('main') . $u->evaluate())));
		} else {
			$t->set(array('main' => '<center>Nothing to display.</center>'));
		}
	} else {
		$t->set(array('main' => '<center>ERROR: ' . mysql_error() . '</center>'));
	}
?>
