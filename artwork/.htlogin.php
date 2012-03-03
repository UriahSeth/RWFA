<?php
//Connect To Database
$hostname='rogerwilliams.db.3892749.hostedresource.com';
$username='rogerwilliams';
$password='Williamsmob1';
$dbname='rogerwilliams';

mysql_connect($hostname,$username, $password) OR DIE ('Unable to connect to database! Please try again later.');
mysql_select_db($dbname);
?>
