<form action="passhasher.php" method="post"><input type="password" name="pass" value=""><br/><input type="submit" value="Submit"><br/><br/></form>

<?php
require_once("php_includes/base.inc.php");
$salt = genSalt();
if(isset($_POST['pass'])) {
	echo "Hash: ".hashPass($_POST['pass'], $salt)."<br/>Salt: ".$salt;
}
?>