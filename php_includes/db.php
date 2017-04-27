<?php
//error_reporting(E_ALL);
ini_set('display_errors',0);
ini_set('display_startup_errors',0);
define('HOST', "localhost");
define('USER', "root");
define('PASS', "");
define('DB', "police");

function genPDO($DB = DB, $user = USER, $pass = PASS, $host = HOST) {
	$pdo = new PDO("mysql:host=".$host.";dbname=".$DB, $user, $pass);
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	return $pdo;
}

$pdo = genPDO();
?>