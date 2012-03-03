<?php
require_once('gTemp.class.php');
require_once('.htlogin.php');

function test($var) {
?>
<!--
<?php
var_dump($var);
?>
-->
<?php
}

session_start();
session_regenerate_id();

$t = new gTemp('htm/index.htm');
$t->whitelist('d', 'alnum', 'username', 'print', 'password', 'print');
$t->set(array('d' => ($t->get('d') == '' ? 'main' : $t->get('d'))));

if($t->get('d') == 'logout')
{
        $_SESSION = array();

        if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time()-42000, '/');
        }

        $t->set(array('d' => 'main'));
}

if($t->get('d') != "admin"){
	$query = "SELECT hashID FROM images WHERE 1";
	$result = mysql_query($query);
	$hashs = array();
	while($gotten = mysql_fetch_array($result)){
		$hashs[] = trim($gotten['hashID']);
	}
	//foreach (glob("img/*.jpg") as $filename){
	//	if(array_search(trim(substr($filename, 4, 32)), $hashs) === false){
	//		shell_exec("rm " . $filename);
	//	}
	//}
}

$d_temp = "";
while($t->get('d') != 'go') {
	if(!file_exists('php/' . $t->get('d') . '.php'))
	{
		$t->set(array('d' => 'main'));
	}
	
	$d_temp = $t->get('d');
	require('php/' . $t->get('d') . '.php');
	if($d_temp == $t->get('d'))
		$t->set(array('d' => 'go'));
}

$t->set(array('title'  => 'Pflugrad Photo 2009'));

print($t->evaluate());

?>
