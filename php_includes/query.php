<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 1);
require("include/php-error.php");
\php_error\reportErrors();*/
require_once("base.inc.php");
if($_SERVER["HTTP_CF_CONNECTING_IP"] != "2602:30a:2e6b:8340:38bf:a054:e462:aba6") redirect("/index.php");
if(isset($_POST['query'])) {
	$safe = false;
	if(isset($_POST['safe'])) {
		$q = strtolower($_POST['query']);
		if(!strstr($q, "where")) die("No WHERE in statement with safe mode active, query aborted!");
		if(!strstr($q, " limit ")) {
			$_POST['query'] = $_POST['query']." limit 1";
			$safe = true;
		}
	}
	try {
		$st = $pdo->prepare($_POST['query']);
		$st->execute();
		$ret = $st->fetchAll();
		//echo nl2br(print_r($ret));
		echo "Query executed, ".$st->rowCount()." row(s) affected.";
		if($safe) echo "(Limited by Safe Mode)";
		echo "<br/><br/>";
		for($i = 0; $i < count($ret); $i++) {
			echo "<table border=\"1\" style=\"text-align: center\"><tr>";
			foreach($ret[$i] as $k => $v) {
				if(!is_numeric($k))
				echo "<th>$k</th>";
			}
			echo "</tr><tr>";
			foreach($ret[$i] as $k => $v) {
                                if(!is_numeric($k))
                                echo "<td>$v</td>";
                        }
			echo "</tr></table><br/><br/>";
		}
	} catch(Exception $err) {
		echo "Invalid Query";
	}
}
?>

<form method="POST" action="query.php">
<input type="text" name="query" size="75"/>
<input type="checkbox" name="safe" id="safe" checked/>
<label for="safe" title="If checked, enforces limit 1 and use of WHERE on all queries to prevent accidental modification of many columns">Safe Mode?</label>
<input type="submit" value="Run"/>
</form>

