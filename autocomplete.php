<?php
require_once("php_includes/base.inc.php");
if(!isset($_GET['name'])) die();
$name = $_GET['name'];
if(strstr($name, "%")) die();
if(strlen($name) < 2) die();
$name .= "%";
$stmt = $pdo->prepare("SELECT `name` FROM `civs` WHERE `name` LIKE :name ORDER BY `scount` DESC LIMIT 10");
$stmt->bindParam(":name", $name);
$stmt->execute();
if(!$stmt->rowCount()) die();
$res = $stmt->fetchAll();
$stmt->closeCursor();
$cnt = count($res);
$rstr = "";
for($i = 0; $i < $cnt; ++$i) {
	$rstr .= "<option value=\"".$res[$i]['name']."\"></option>";
}
echo $rstr;
die();
?>