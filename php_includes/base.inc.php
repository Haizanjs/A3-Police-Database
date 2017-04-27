<?php
require('db.php');

/* <---UNCOMMENT BELOW IF YOU USE SSL--->
if(!isset($_SERVER['HTTPS']) && !strstr($_SERVER["HTTP_CF_VISITOR"], "https")) {
	header("Location: https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", true, 303);
	exit();
}
*/

if(isset($_SERVER["HTTP_CF_CONNECTING_IP"])) $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];

define('U_ID', 0);
define('U_UNAME', 1);
define('U_DNAME', 2);
define('U_PASS', 4);
define('U_PHONE', 5);
define('U_BADGE', 6);
define('TERM_ID', -2);
define('A_SUCCESS', 0);
define('A_INFO', 1);
define('A_WARN', 2);
define('A_DANGER', 3);
define('L_TERM', 0);
define('L_PRODEMO', 1);
define('L_TRANS', 2);
define('L_EXP', 3);
define('L_LOGIN', 4);
define('L_NAME', 5);
define('PENDING', 9);
define('HOUR', 3600);
define('DAY', 86400);
define('WEEK', 604800);
define('YEAR', 31556926);


/*require( 'php_error.php' );
\php_error\reportErrors();*/

session_start();
$luser = 0;
if(isLoggedIn()) {
	$luser = getUser($_SESSION['uid'], U_ID);
	$_SESSION['uname'] = $luser['uname'];
	if(time()-$_SESSION['ll'] > HOUR*6) $pdo->exec("UPDATE `users` SET `LastLogin` = NOW() WHERE `id` = '$luser[id]'");
} else checkRem();

$_SESSION['ll'] = time();

function redirect($url, $time = 0) {
	echo "<META http-equiv=\"refresh\" content=\"$time;URL=$url\">";
}

function remember($uid) {
	global $pdo;
	$str = genSalt(20);
	$stmt = $pdo->prepare("DELETE FROM `remember` WHERE `uid` = :id");
	$stmt->bindParam(":id", $uid);
	$stmt->execute();
	setcookie("r", $str, strtotime('+2 week'), "/");
	$str = hash("sha256", $str);
	$stmt = $pdo->prepare("INSERT INTO `remember` (`uid`, `hash`, `expire`, `ip`) VALUES (:uid, :hash, NOW() + INTERVAL 2 WEEK, :ip)");
	$stmt->bindParam(":uid", $uid);
	$stmt->bindParam(":hash", $str);
	$stmt->bindParam(":ip", $_SERVER['REMOTE_ADDR']);
	$stmt->execute();
	return $stmt->rowCount();
}

function checkRem() {
	global $pdo;
	if(isset($_COOKIE['r'])) {
		$rem = hash("sha256", $_COOKIE['r']);
		$stmt = $pdo->prepare("SELECT * from `remember` WHERE `hash` = :hash");
		$stmt->bindParam(":hash", $rem);
		$stmt->execute();
		
		if(!$stmt->rowCount()) return false;
		
		$res = $stmt->fetch();
		
		if($res['ip'] != $_SERVER['REMOTE_ADDR']) {
			forget();
			return false;
		}
		
		$usr = getUser($res['uid'], U_ID);
		
		$_SESSION['uname'] = $usr['uname'];
		$_SESSION['uid'] = $usr['id'];
		$_SESSION['perms'] = $usr['plevel'];
		$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
		$pdo->exec("UPDATE `users` SET `LastLogin` = NOW(), `ip` = '$_SERVER[REMOTE_ADDR]' WHERE `id` = '$usr[id]'");
	} else return false;
	return true;
}

function forget() {
	global $pdo;
	$stmt = $pdo->prepare("DELETE FROM `remember` WHERE `hash` = :hash");
	$stmt->bindParam(":hash", hash("sha256", $_COOKIE['r']));
	$stmt->execute();
	setcookie("r", "", 1, "/");
}

function is_session_started() {
	if ( php_sapi_name() !== 'cli' ) {
		if ( version_compare(phpversion(), '5.4.0', '>=') ) {
			return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
		} else {
			return session_id() === '' ? FALSE : TRUE;
		}
	}
	return FALSE;
}

function isLoggedIn() {
	return isset($_SESSION['uid']) && !empty($_SESSION['uid']);
}

function antiXSS($str) {
	return htmlspecialchars(strip_tags(urldecode($str)));
}

function showAlert($txt, $type = A_INFO) {
	$txt = antiXSS($txt);
	$tp = "";
	$icls = "";
	switch($type) {
		case 0:
		$tp = "success";
		$icls = "fa fa-check-circle";
		break;
		case 1:
		$tp = "info";
		$icls = "fa fa-info-circle";
		break;
		case 2:
		$tp = "warning";
		$icls = "fa fa-exclamation-triangle";
		break;
		case 3:
		$tp = "danger";
		$icls = "fa fa-exclamation-circle";
		break;
		default:
		$tp = "info";
	}
	echo "<div class=\"alert alert-$tp fade in\">
												<i class=\"$icls\"></i>
												<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
												<p>$txt</p>
											</div>";
}

function safeNum($num) {
	return intval(str_replace(Array("$", ".", ","), Array("", "", ""), $num));
}

/*function hasPerm($permname) { // OLD HASPERM FUNCTION
	global $pdo;
	if(!isLoggedIn()) return false;
	$stmt = $pdo->prepare("SELECT `plevel`,`dept`, `info`, `rank` FROM `users` WHERE `uname` = :name");
	$stmt->bindParam(":name", $_SESSION['uname']);
	$stmt->execute();
	$res = $stmt->fetch();
	$stmt->closeCursor();
	$stmt = $pdo->prepare("SELECT `perms`,`info` FROM `dept` WHERE `id` = :dept");
	$stmt->bindParam(":dept", $res['dept']);
	$stmt->execute();
	$dp = $stmt->fetch();
	$stmt->closeCursor();

	$dptinf = json_decode($dp['info'], true);
	if(intval($res['rank']) >= intval($dptinf['cmdrank']) && $permname == "pdcmd") return true;
	
	$dperms = json_decode($dp['perms']);
	$uperms = json_decode($res['plevel']);
	$perms = array_merge($uperms, $dperms);
	if(in_array("all", $perms)) return true;
	if(in_array($permname, $perms)) return true;
	return false;
}*/

function hasPerm($permname) {
	global $pdo;
	if(!isLoggedIn()) return false;
	// GET USER DATA / FETCH
	$stmt = $pdo->prepare("SELECT `plevel`,`dept`,`rank` FROM `users` WHERE `id` = :name");
	$stmt->bindParam(":name", $_SESSION['uid']);
	$stmt->execute();
	if(isset($debug)) echo 'Init: '.var_dump($stmt->count());
	$res = $stmt->fetch();
	$stmt->closeCursor();
	// GET DEPARTMENT DATA / FETCH
	$stmt = $pdo->prepare("SELECT `perms`,`info` FROM `dept` WHERE `id` = :dept");
	$stmt->bindParam(":dept", $res['dept']);
	$stmt->execute();
	if(isset($debug)) echo ' Init2: '.var_dump($stmt->count());
	$dp = $stmt->fetch();
	$stmt->closeCursor();

	// RANK COMMAND SYSTEM
	$dptinf = json_decode($dp['info'], true);
	if(intval($res['rank']) >= intval($dptinf['cmdrank']) && $permname == "pdcmd") return true;
	
	// MERGE PERMISSION ARRAYS FROM DEPT AND USERS TO CREATE ONE PERMISSION ARRAY, SEARCH FOR PERMISSION IN MERGED ARRAY
	$dperms = json_decode($dp['perms']);
	$uperms = json_decode($res['plevel']);
	$perms = array_merge($uperms, $dperms);
	if(in_array("all", $perms)) return true;
	if(in_array($permname, $perms)) return true;
	return false;
}

function getRequests($uid = 0) {
	global $pdo;
	if(!$uid) {
		$stmt = $pdo->prepare("SELECT * FROM `requests`");
		$stmt->execute();
	} else {
		$stmt = $pdo->prepare("SELECT * FROM `requests` WHERE `uid` = :uid");
		$stmt->bindParam(":uid", $uid);
		$stmt->execute();
	}
	return $stmt->fetchAll();
}

function createRequest($type, $value) {
	global $pdo;
	$usr = getUser($_SESSION['uid'], U_ID);
	$req = Array("type" => $type, "value" => $value);
	$reqjson = json_encode($req);
	$stmt = $pdo->prepare("INSERT INTO `requests` (`data`, `uid`) VALUES (:req, :uid)");
	$stmt->bindParam(":req", $reqjson);
	$stmt->bindParam(":uid", $usr['id']);
	$stmt->execute();
}

function deleteRequest($rid) {
	global $pdo;
	$stmt = $pdo->prepare("DELETE FROM `requests` WHERE `id` = :rid LIMIT 1");
	$stmt->bindParam(":rid", $rid);
	$stmt->execute();
	return $stmt->rowCount();
}

function processRequest($rid) {
	global $pdo;
	$stmt = $pdo->prepare("SELECT * FROM `requests` WHERE `id` = :rid");
	$stmt->bindParam(":rid", $rid);
	$stmt->execute();
	if(!$stmt->rowCount()) return false;
	$res = $stmt->fetch();
	$rjs = json_decode($res['data'], true);
	$rc = 0;
	switch($rjs['type']) {
		case "dept":
		$fdpt = getDeptName($res['uid']);
		$stmt = $pdo->prepare("UPDATE `users` SET `dept` = :value WHERE `id` = :uid");
		$stmt->bindParam(":value", $rjs['value']);
		$stmt->bindParam(":uid", $res['uid']);
		$stmt->execute();
		$rc = $stmt->rowCount();
		$dpt = getDept($rjs['value']);
		/*logAction(null, $res['uid'], L_TRANS, json_encode(Array($fdpt, $dpt['dname'])));*/
		break;
		case "name":
		$rusr = getUser($res['uid'], U_ID);
		$stmt = $pdo->prepare("UPDATE `users` SET `display` = :value WHERE `id` = :uid");
		$stmt->bindParam(":value", $rjs['value']);
		$stmt->bindParam(":uid", $res['uid']);
		$stmt->execute();
		$rc = $stmt->rowCount();
		/*logAction(null, $res['uid'], L_NAME, json_encode(Array($rusr['display'], $rjs['value'])));*/
		break;
		default: return false;
	}
	$stmt->closeCursor();
	if($rc) {
		$stmt = $pdo->prepare("DELETE FROM `requests` WHERE `id` = :rid LIMIT 1");
		$stmt->bindParam(":rid", $rid);
		$stmt->execute();
	}
	return $rc;
}

function genSalt($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; ++$i) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

function hashPass($pass, $salt) {
	return md5(md5($pass).$salt);
}

function getCiv($name, $method = U_DNAME) {
	global $pdo;
	$mthd = null;
	switch($method) {
		case U_DNAME:
			$mthd = "name";
		break;
		case U_ID:
			$mthd = "id";
		break;
		default: return false;
	}
	$stmt = $pdo->prepare("SELECT * FROM `civs` WHERE `$mthd` = :name");
	$stmt->bindParam(":name", $name);
	$stmt->execute();
	if($stmt->rowCount())
		return $stmt->fetch();
	return false;
}

function createCiv($name) {
	if(getCiv($name)) return false;
	global $pdo;
	$stmt = $pdo->prepare("INSERT INTO `civs` (`name`) VALUES (:name)");
	$stmt->bindParam(":name", $name);
	$stmt->execute();
	if($stmt->rowCount()) {
		$stmt->closeCursor();
		return getCiv($name);
	} else return false;
}

// Case 0 = List all records for specific person that are not expunged
// Case 1 = List only records for specific person not processed by DOC and are not expunged
// Case 2 = List only specific record for specific person
// Case 3 = List only records for specific person that are expunged
// Case 4 = List ALL records for specific person reguardless of status
function getArrests($crid,$q=0) {
	global $pdo;
	switch ($q){
		case 0: $stmt = $pdo->prepare("SELECT * FROM `arrests` WHERE `uid` = :crid AND `exp` = 0 ORDER BY `id` DESC"); break;
		case 1: $stmt = $pdo->prepare("SELECT * FROM `arrests` WHERE `uid` = :crid AND `exp` = 0 AND `proc` = 0 ORDER BY `id` DESC"); break;
		case 2: $stmt = $pdo->prepare("SELECT * FROM `arrests` WHERE `id` = :crid ORDER BY `id` DESC"); break;
		case 3: $stmt = $pdo->prepare("SELECT * FROM `arrests` WHERE `uid` = :crid AND `exp` != 0 ORDER BY `id` DESC"); break;
		case 4: $stmt = $pdo->prepare("SELECT * FROM `arrests` WHERE `uid` = :crid ORDER BY `id` DESC"); break;
	}
	$stmt->bindParam(":crid", $crid);
	$stmt->execute(); 
	if(!$stmt->rowCount()) return false;
	return $stmt->fetchAll();
}

// Case 0 = List all records for specific person that are not expunged
function getWarrants($crid,$q=0) {
	global $pdo;
	switch ($q){
		case 0: $stmt = $pdo->prepare("SELECT * FROM `warrants` WHERE `uid` = :crid AND `active` = 0 ORDER BY `id` DESC"); break;
	}
	$stmt->bindParam(":crid", $crid);
	$stmt->execute(); 
	if(!$stmt->rowCount()) return false;
	return $stmt->fetchAll();
}

function getBolos($histLimit, $active = false) {
	global $pdo;
	$format = "Y-m-d H:i:s";
	$sec = $histLimit * 3600;
	$lapsed = gmdate($format, time() - $sec);
	if($active == true){
		$stmt = $pdo->prepare("SELECT * FROM `bolo` WHERE `RealDate` >= :lapsed AND `canceled` = '0' ORDER BY `id` DESC");
	}else{
		$stmt = $pdo->prepare("SELECT * FROM `bolo` WHERE `RealDate` >= :lapsed ORDER BY `id` DESC");
	}
	$stmt->bindParam(":lapsed", $lapsed);
	$stmt->execute();
	if(!$stmt->rowCount()) return false;
	return $stmt->fetchAll();
}

function getTraffic($trac) {
	global $pdo;
	$stmt = $pdo->prepare("SELECT * FROM `traffic` WHERE `civid` = :trac ORDER BY `id` DESC");
	$stmt->bindParam(":trac", $trac);
	$stmt->execute();
	if(!$stmt->rowCount()) return false;
	return $stmt->fetchAll();
}

// * ACCEPTS U_UNAME, U_DNAME, OR U_ID ACCORDINGLY DEPENDING ON WANTED CRITERIA
function getUser($criteria, $method = U_DNAME) {
	global $pdo;
	$mthd = null;
	
	switch($method) {
		case U_DNAME:
			$mthd = "display";
		break;
		case U_UNAME:
			$mthd = "uname";
		break;
		case U_ID:
			$mthd = "id";
		break;
		default: return false;
	}
	$stmt = $pdo->prepare("SELECT * FROM `users` WHERE `$mthd` = :crit");
	$stmt->bindParam(":crit", $criteria);
	$stmt->execute();
	if($stmt->rowCount()) return $stmt->fetch();
	return false;
}

function newBond($crid, $cdate, $amt) {
	global $pdo;
	$stmt = $pdo->prepare("INSERT INTO `bonds` (`citid` ,`cdate` ,`bondamnt` ,`resolved`) VALUES (:crid, :cdate, :amt, '0')");
	$stmt->bindParam(":crid", $crid);
	$stmt->bindParam(":cdate", $cdate);
	$stmt->bindParam(":amt", $amt);
	$stmt->execute();
	$stmt->closeCursor();
	$stmt = $pdo->prepare("SELECT `id` FROM `bonds` WHERE `citid` = :citid ORDER BY `id` DESC");
	$stmt->bindParam(":citid", $crid);
	$stmt->execute();
	$bid = $stmt->fetch();
	return $bid['id'];
}

function newArrest($crid, $crimes, $evd, $time, $date, $bailbond, $bail) {
	global $pdo;
	$bid = -1;
	if($bailbond == "Bond") {
		$bid = newBond($crid, '2099-01-01', $bail);
		$bail = 0;
	}
	$cop = getUser($_SESSION['uid'], U_ID);
	$stmt = $pdo->prepare("INSERT INTO `arrests` (`uid` ,`copid` ,`docid` ,`date` ,`time` ,`bondid` ,`proc` ,`crimes` ,`evd` ,`bail` ,`plea` ,`exp` ,`dojid` ,`RealDate`) VALUES (:uid, :copid, '0', :date, :time, :bondid, '0', :crimes, :evd, :bail, '0', '0', '0', UTC_TIMESTAMP())");
	$stmt->bindParam(":uid", $crid);
	$stmt->bindParam(":copid", $cop['id']);
	$stmt->bindParam(":date", $date);
	$stmt->bindParam(":time", $time);
	$stmt->bindParam(":bondid", $bid);
	$stmt->bindParam(":crimes", $crimes);
	$stmt->bindParam(":evd", $evd);
	$stmt->bindParam(":bail", $bail);
	$stmt->execute();
	$stmt->closeCursor();
	$stmt = $pdo->prepare("SELECT `id` FROM `arrests` WHERE `uid` = :id ORDER BY `id` DESC");
	$stmt->bindParam(":id", $crid);
	$stmt->execute();
	$arrinfo = $stmt->fetch();
	$stmt->closeCursor();
	return $arrinfo;
}

function newWarrant($crid, $dojname, $warrant, $wtype, $date, $wlink) {
	global $pdo;
	$cop = getUser($_SESSION['uid'], U_ID);
	$stmt = $pdo->prepare("INSERT INTO `warrants` (`uid` ,`copid` ,`dojname` ,`date` ,`crimes` ,`wtype` ,`wlink` ,`active` ,`RealDate`) VALUES (:uid, :copid, :dojname, :date, :crimes, :wtype, :wlink, '0', UTC_TIMESTAMP())");
	$stmt->bindParam(":uid", $crid);
	$stmt->bindParam(":copid", $cop['id']);
	$stmt->bindParam(":dojname", $dojname);
	$stmt->bindParam(":date", $date);
	$stmt->bindParam(":crimes", $warrant);
	$stmt->bindParam(":wtype", $wtype);
	$stmt->bindParam(":wlink", $wlink);
	$stmt->execute();
	$stmt->closeCursor();
	$stmt = $pdo->prepare("SELECT `id` FROM `warrants` WHERE `uid` = :id ORDER BY `id` DESC");
	$stmt->bindParam(":id", $crid);
	$stmt->execute();
	$stmt->closeCursor();
}

function newIntake($crid) {
	global $pdo;
	$cop = getUser($_SESSION['uid'], U_ID);
	$stmt = $pdo->prepare("UPDATE `arrests` SET `docid` = :copid, `proc` = '1' WHERE `id` = :id");
	$stmt->bindParam(":id", $crid);
	$stmt->bindParam(":copid", $cop['id']);
	$stmt->execute();
}

function newBolo($info) {
	global $pdo;
	$cop = getUser($_SESSION['uid'], U_ID);
	$stmt = $pdo->prepare("INSERT INTO `bolo` (`copid` ,`canceled` ,`info` ,`RealDate`) VALUES (:copid, '0', :info, UTC_TIMESTAMP())");
	$stmt->bindParam(":copid", $cop['id']);
	$stmt->bindParam(":info", $info);
	$stmt->execute();
	$stmt->closeCursor();
	$stmt = $pdo->prepare("SELECT `id` FROM `bolo` WHERE `info` = :info ORDER BY `id` DESC");
	$stmt->bindParam(":info", $info);
	$stmt->execute();
	$arrinfo = $stmt->fetch();
	$stmt->closeCursor();
	return $arrinfo;
}

function cancelBolo($boid) {
	global $pdo;
	$doj = getUser($_SESSION['uid'], U_ID);
	$stmt = $pdo->prepare("UPDATE `bolo` SET `canceled` = :dojid WHERE `id` = :id");
	$stmt->bindParam(":id", $boid);
	$stmt->bindParam(":dojid", $doj['id']);
	$stmt->execute();
}

function newTraffic($trac, $reason, $date, $ticket, $notes) {
	global $pdo;
	$tid = -1;
	$cop = getUser($_SESSION['uid'], U_ID);
	$stmt = $pdo->prepare("INSERT INTO `traffic` (`civid` ,`copid` ,`date` ,`reason` ,`ticket` ,`notes` ,`RealDate`) VALUES (:civid, :copid, :date, :reason, :ticket, :notes, UTC_TIMESTAMP())");
	$stmt->bindParam(":civid", $trac);
	$stmt->bindParam(":copid", $cop['id']);
	$stmt->bindParam(":date", $date);
	$stmt->bindParam(":reason", $reason);
	$stmt->bindParam(":ticket", $ticket);
	$stmt->bindParam(":notes", $notes);
	$stmt->execute();
	$stmt->closeCursor();
	$stmt = $pdo->prepare("SELECT `id` FROM `traffic` WHERE `civid` = :id ORDER BY `id` DESC");
	$stmt->bindParam(":id", $trac);
	$stmt->execute();
	$trainfo = $stmt->fetch();
	$stmt->closeCursor();
	return $trainfo;
}

function updateExpunged($arid) {
	global $pdo;
	$cop = getUser($_SESSION['uid'], U_ID);
	$me = $_SESSION['uid'];
	$stmt = $pdo->prepare("UPDATE `arrests` SET `exp` = $me, `dojid` = :dojid, `date` = :date WHERE `id` = :id");
	$stmt->bindParam(":id", $arid);
	$stmt->bindParam(":date", date("Y-m-d"));
	$stmt->bindParam(":dojid", $cop['id']);
	$stmt->execute();
}

function updateWarrant($arid) {
	global $pdo;
	$cop = getUser($_SESSION['uid'], U_ID);
	$me = $_SESSION['uid'];
	$stmt = $pdo->prepare("UPDATE `warrants` SET `active` = 1 WHERE `id` = :id");
	$stmt->bindParam(":id", $arid);
	$stmt->execute();
}

/*
* REGISTRATION RETURN VALUES
* false = SUCCESS
* 1 = DUPLICATE USERNAME
* 2 = DUPLICATE DISPLAY NAME(RP NAME)
* 3 = DUPLICATE EMAIL
* 4 = QUERY ERROR
*/ 
function regUser($uname, $disname, $pass, $email) {
	global $pdo;
	
	$salt = genSalt();
	$phash = hashPass($pass, $salt);
	$stmt = $pdo->prepare("SELECT * FROM `users` WHERE `display` = :name OR `uname` = :uname OR `email` = :email");
	
	$civid = getCiv($disname);
	if(!$civid) $civid = createCiv($disname);
	
	$stmt->bindParam(":name", $disname);
	$stmt->bindParam(":uname", $uname);
	$stmt->bindParam(":email", $email);
	$stmt->execute();
	if($stmt->rowCount()) {
		$user = $stmt->fetch();
		if($user['uname'] == $uname) return 1;
		if($user['display'] == $disname) return 2;
		if($user['email'] == $email) return 3;
	}
	$stmt->closeCursor();
	$stmt = $pdo->prepare("INSERT INTO `users` (`citid`, `RegiDate`, `LastLogin`, `uname`, `display`, `phash`, `salt`, `ip`, `email`, `plevel`) VALUES (:civid, NOW(), NOW(), :uname, :disname, :phash, :psalt, '$_SERVER[REMOTE_ADDR]', :email, '[\"none\"]')");
	$stmt->bindParam(":civid", $civid['id']);
	$stmt->bindParam(":uname", $uname);
	$stmt->bindParam(":disname", $disname);
	$stmt->bindParam(":phash", $phash);
	$stmt->bindParam(":psalt", $salt);
	$stmt->bindParam(":email", $email);
	$stmt->execute();
	if($stmt->rowCount()) return false;
	return 4;
}

/*
* LOGIN RETURN VALUES
* false = SUCCESS
* 1 = USER DOES NOT EXIST
* 2 = PASSWORD INCORRECT
*/
function login($uname, $pass, $rem = true) {
	global $pdo;
	
	$usr = getUser($uname, U_UNAME);
	if(!$usr) return 1;
	if($usr['phash'] != hashPass($pass, $usr['salt'])) return 2;
	$pdo->exec("UPDATE `users` SET `LastLogin` = NOW(), `ip` = '$_SERVER[REMOTE_ADDR]' WHERE `id` = '$usr[id]'");
	$_SESSION['uname'] = $usr['uname'];
	$_SESSION['uid'] = $usr['id'];
	$_SESSION['perms'] = $usr['plevel'];
	$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
	if($rem) remember($_SESSION['uid']);
	/*logAction($usr['id'], -1, L_LOGIN, null);*/
	return false;
}

function logout() {
	if(isset($_COOKIE['r'])) forget();
	if(is_session_started()) {
		session_unset();
		session_destroy();
	}
}

function setUData($newVal, $type = -1, $uid = 0) {
	global $pdo;
	if(!$uid) $uid = $_SESSION['uid'];
	$dtype = NULL;
	$stmt = 0;
	switch($type) {
		case U_PASS:
		$salt = genSalt();
		$pass = hashPass($newVal, $salt);
		$stmt = $pdo->prepare("UPDATE `users` SET `phash` = :pass, `salt` = :salt WHERE `id` = :uid");
		$stmt->bindParam(":pass", $pass);
		$stmt->bindParam(":salt", $salt);
		$stmt->bindParam(":uid", $uid);
		$stmt->execute();
		break;
		case U_DNAME:
		$dtype = "display";
		break;
		case U_PHONE:
		$dtype = "phone";
		break;
		case U_BADGE:
		$dtype = "badge";
		break;
		case U_UNAME:
		$dtype = "uname";
		break;
		default: return false;
	}
	if($dtype != NULL) {
		$stmt = $pdo->prepare("UPDATE `users` SET `$dtype` = :newVal WHERE `id` = :uid");
		$stmt->bindParam(":uid", $uid);
		$stmt->bindParam(":newVal", $newVal);
		$stmt->execute();
	}
	return $stmt->rowCount();
}

function getDepts($auth = 35, $inorder = false, $section = 0) {
	global $pdo;
	$stmt = NULL;
	$ostr = " ORDER BY `authority` DESC";
	if($inorder) $ostr = " ORDER BY `id` ASC";
	$stmt = $pdo->prepare("SELECT * FROM `dept` WHERE `authority` < $auth AND `section` = 0 OR `section` = 2".$ostr);
	$stmt->execute();
	return $stmt->fetchAll();
}

function getDept($dept) {
	global $pdo;
	$stmt = $pdo->prepare("SELECT * FROM `dept` WHERE `id` = '$dept' AND `section` = 0 OR `section` = 2 ORDER BY `authority` DESC");
	$stmt->execute();
	return $stmt->fetch();
}

function getRankName($uid) {
	global $pdo;
	$stmt = $pdo->prepare("SELECT `dept`,`rank` FROM `users` WHERE `id` = :uid");
	$stmt->bindParam(":uid", $uid);
	$stmt->execute();
	if(!$stmt->rowCount()) return false;
	$ur = $stmt->fetch();
	$stmt->closeCursor();
	$depts = getDept($ur['dept']);
	$ranks = json_decode($depts['ranks']);
	return $ranks[$ur['rank']];
}

function getDeptRanks($dept) {
	global $pdo;
	$stmt = $pdo->prepare("SELECT `rank` FROM `users` WHERE `id` = :dept ORDER BY `authority` DESC");
	$stmt->bindParam(":dept", $dept);
	$stmt->execute();
	if(!$stmt->rowCount()) return false;
	return $stmt->fetch();
}

function getDeptName($uid) {
	global $pdo;
	$stmt = $pdo->prepare("SELECT `dept` FROM `users` WHERE `id` = :uid");
	$stmt->bindParam(":uid", $uid);
	$stmt->execute();
	if(!$stmt->rowCount()) return false;
	$ur = $stmt->fetch();
	$stmt->closeCursor();
	$depts = getDept($ur['dept']);
	return $depts['dname'];
}

function verifyUser($uid, $action = 0, $dept = 1, $rank = 0) {
	global $pdo;
	switch($action) {
		case 0: return false;
		case 2:
		$dept = TERM_ID;
		$rank = TERM_ID;
		break;
		default:
		break;
	}
	$user = getUser($uid, U_ID);
	if($user['dept'] != -1) return false;
	$stmt = $pdo->prepare("UPDATE `users` SET `rank` = :rank, `dept` = :dept WHERE `id` = :uid");
	$stmt->bindParam(":uid", $uid);
	$stmt->bindParam(":rank", $rank);
	$stmt->bindParam(":dept", $dept);
	$stmt->execute();
	return $stmt->rowCount();
}

function copsByDept($dept) {
	global $pdo;
	$stmt = $pdo->prepare("SELECT `id`, `rank`, `display`, `badge`, `LastLogin` FROM `users` WHERE `dept` = :dept ORDER BY `rank` DESC");
	$stmt->bindParam(":dept", $dept);
	$stmt->execute();
	if(!$stmt->rowCount()) return false;
	return $stmt->fetchAll();
}

function titleFormat($str) {
	$str = ucwords($str);
	return str_replace(Array(" Of ", " A ", " In ", " An ", " To ", " On "), Array(" of ", " a ", " in ", " an ", " to ", " on "), $str);
}

function getInfo($col) {
	global $pdo;
	$stmt = $pdo->prepare("SELECT `data` FROM `info` WHERE `name` = :name");
	$stmt->bindParam(":name", $col);
	$stmt->execute();
	if(!$stmt->rowCount()) return false;
	return $stmt->fetch();
}

function setInfo($col, $data) {
	global $pdo;
	$stmt = $pdo->prepare("UPDATE `info` SET `data` = :data WHERE `name` = :col");
	$stmt->bindParam(":data", $data);
	$stmt->bindParam(":col", $col);
	$stmt->execute();
	return $stmt->rowCount();
}

/*function setDept($uid, $dept) {
	global $pdo;
	$stmt = $pdo->prepare(
}*/

function fireMember($uid, $log = true) {
	global $pdo;
	/*if($log) logAction($_SESSION['uid'], $uid, L_TERM, getDeptName($uid));*/
	$stmt = $pdo->prepare("UPDATE `users` SET `dept` = '".TERM_ID."', `rank` = '".TERM_ID."' WHERE `id` = :id");
	$stmt->bindParam(":id", $uid);
	$stmt->execute();
	return $stmt->rowCount();
}

function setRank($uid, $nrank, $log = true) {
	global $pdo;
	$stmt = $pdo->prepare("UPDATE `users` SET `rank` = :nrank WHERE `id` = :uid");
	$stmt->bindParam(":uid", $uid);
	$stmt->bindParam(":nrank", $nrank);
	$stmt->execute();
	return $stmt->rowCount();
}

function isSubOf($d1, $d2) {
	global $pdo;
	$stmt = $pdo->prepare("SELECT `info` FROM `dept` WHERE `id` = :d1");
	$stmt->bindParam(":d1", $d1);
	$stmt->execute();
	if(!$stmt->rowCount()) return false;
	$res = $stmt->fetch();
	$subs = json_decode($res['info'], true);
	return in_array(intval($d2), $subs['reporters']);
}

function sCiv($id) {
	global $pdo;
	$id = intval($id);
	$pdo->exec("UPDATE `civs` SET `scount` = `scount` + 1 WHERE `id` = '$id'");
	return;
}

function autoComp($inp, $id, $del = 1000) {
	$acn = genSalt(10);
	$dl = genSalt(10);
	$lv = genSalt(10);
	echo "<script type=\"text/javascript\">
var $lv = \"\";
function $acn() {
	var cVal = document.getElementById(\"$id\").value;
	if(cVal.length >= 2 && $lv != cVal) {
		$.get( \"autocomplete.php?name=\"+document.getElementById(\"$id\").value, function( data ) {
			document.getElementById(\"$acn\").innerHTML = data;
		});
		$lv = cVal;
	}
}
setInterval(\"$acn()\", $del);
</script>
<input autocomplete=\"off\" type=\"text\" list=\"$acn\" id=\"$id\" class=\"form-control\" name=\"$inp\">
<datalist id=\"$acn\"></datalist>";
}

function isCop($scrit, $sval = U_DNAME) {
	$cop = getUser($scrit, $sval);
	if(!$cop) return false;
	if($cop['dept'] == TERM_ID || $cop['dept'] == PENDING) return false;
	return true;
}

/*function formatLog($logid) {
	global $pdo;
	$details = "";
	switch($acid) {
		case L_TERM:
		$details = "%s(UID: %d) terminated %s(UID: %d) from %s";
		break;
		case L_PRODEMO:
		$details = "%s(UID: %d) promoted %s(UID: %d) to %s in %s";
		break;
		case L_TRANS:
		$details = "%s(UID: %d) transferred %s(UID: %d) from %s to %s";
		break;
		case L_LOGIN:
		$details = '%s(UID: %d) has logged in from IP %s';
		break;
		case L_EXP:
		$details = '%s(UID: %d) has expunged %s(UID: %d)\'s records';
		break;
		default:
		$details = '%s(UID: %d) has triggered an unknown log action';
		break;
	}
}*/

/*
function logAction($uid, $oid, $acid, $infoarr) {
	global $pdo;
	if($uid == null) $uid = $_SESSION['uid'];
	$stmt = $pdo->prepare('INSERT INTO `logs` (`uid`, `oid`, `uip`, `time`, `action`, `detaildata`) VALUES (:uid, :oid, \''.$_SERVER['REMOTE_ADDR'].'\', '.time().', :acid, :djson)');
	$stmt->bindParam(':uid', $uid);
	$stmt->bindParam(':oid', $oid);
	$stmt->bindParam(':acid', $acid);
	$stmt->bindParam(':djson', $infoarr);
	$stmt->execute();
	return $stmt->rowCount();
}
*/

if(isLoggedIn() && $_SESSION['ip'] != $_SERVER['REMOTE_ADDR']) logout();
?>
