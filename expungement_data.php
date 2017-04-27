<!doctype html>
<?php
require_once("php_includes/base.inc.php");
if(!hasPerm("doj")) redirect("index.php");
if(!isset($_POST['crim'])){redirect("expungement.php");}

if(isset($_POST['crim'])){$res = trim($_POST['crim']); $des = "list";}
if(isset($_POST['eid'])){$res = trim($_POST['crim']); $exid = trim($_POST['eid']); $des = "expunge";}

if(empty($des)) redirect("expungement.php");

$crid = getCiv($res);
if(!$crid) $crid = createCiv(ucwords($res));
$crim = $crid['name'];
sCiv($crid['id']);
$cminfo = getInfo("cminfo");
$cminfo = json_decode($cminfo['data'], true);

if(!isset($_SESSION['arrandom'])) $_SESSION['arrandom'] = $_POST['random']+10;

if(isset($_COOKIE["LSTZ"])){
	$usrTZ = htmlspecialchars($_COOKIE["LSTZ"]);
}else{
	$usrTZ = "UTC";
}
?>
<html lang="en-US">
	<head>

		<!-- Meta -->
		<meta charset="UTF-8">
		<title><?php echo $cminfo['pda']; ?> - Expungement Database</title>
		<meta name="description" content="<?php echo $cminfo['pdn']; ?> - Department of Corrections">
		<meta name="author" content="Cole, Scott Harm (Retired)">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- Favicons -->
		<link rel="shortcut icon" href="img/favicons/favicon.png">
		<link rel="apple-touch-icon" href="img/favicons/icon.png">
		<link rel="apple-touch-icon" sizes="72x72" href="img/favicons/72x72.png">
		<link rel="apple-touch-icon" sizes="114x114" href="img/favicons/114x114.png">
		
		<!-- CSS -->
		<link rel="stylesheet" href="css/reset.css">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<link href="https://fonts.googleapis.com/css?family=Raleway:300|Muli:300" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="css/idangerous.swiper.css">
		<link rel="stylesheet" href="css/style.css">
		
		<!-- Scripts -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	</head>
	<body>
		<div id="overlay"></div>
		<div id="top">
			<a href="#" id="sidebar-button"></a>
			<header id="logo">
				<img src="img/logo.png" alt="Logo">
			</header>
		</div>
		<div id="main-wrapper">
			<div id="content">
				<div class="container-fluid">
					<div id="heading" class="row">
						<div class="col-12">
							<header>
								<h1>Department of Justice Expungement Database</h1>
								<h2>Mess up a record? Please tell Cole for now.</h2>
							</header>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<article class="inner">
								<div class="row">
								  <div class="col-12">
									<h4>Choose Record to Expunge for <?php echo ucwords($crim); ?></h4>
									</div>
								</div>
								<center>
                            <center>
							  <?php
                              $arrests = getArrests($crid['id'], 4);					  
							  if($arrests){
							  	if($des == "expunge"){
							  		$updateExp = updateExpunged($_POST['eid']);
							  		echo "<h5>".$crim."'s record has been expunged.</h5>";
							  	}
							  	$arrests = getArrests($crid['id'], 3);
							  if($arrests){
							  ?>
							  <table style="text-align: center">
								  <tbody>
									<tr>
										<th>Time Entered</th>
										<th>Name</th>
										<th>Crime</th>
										<th>Time</th>
										<th>Bail</th>
										<th>Bond</th>
										<th>DOJ Auth</th>
										<th>Expunged Date</th>
                                        <th>Status</th>
									</tr>
									<?php
									$format = "Y-m-d H:i:s";
									$arrests = getArrests($crid['id'], 3);
									$acnt = count($arrests);
									for($i = 0; $i < $acnt; $i++) {
										$aoffi = getUser($arrests[$i]['copid'], U_ID);
										if($arrests[$i]['exp'] != 0) $ajug = getUser($arrests[$i]['exp'], U_ID);
										if(!$ajug) $ajug = "Unknown";
										else $ajug = $ajug['display'];
										$utcTS = antiXSS($arrests[$i]['RealDate']);
										$usrTS = date_create($utcTS, new DateTimeZone("UTC")) -> setTimeZone(new DateTimeZone($usrTZ)) -> format($format);
										$exid = $arrests[$i]['id'];
										($arrests[$i]['bondid'] == -1) ? $bond = "No" : $bond = "Yes";
										if($arrests[$i]['bail'] == 0) $bail = "No"; else $bail = "$".number_format($arrests[$i]['bail']);
										echo "<tr><td>".$usrTS."</td><td>$crim</td><td>".titleFormat(antiXSS($arrests[$i]['crimes']))."</td><td>".antiXSS(number_format($arrests[$i]['time']))."</td><td>$bail</td><td>$bond</td><td>Authorized ($ajug)</td><td>".antiXSS($arrests[$i]['date'])."</td><td>Expunged</td></tr>";
									} ?>
								  </tbody>
							  </table>
							  <?php }
							  	$arrests = getArrests($crid['id']);
							  	if($arrests){				  
							  ?>
							  <table style="text-align: center">
								  <tbody>
									<tr>
										<th>Time Entered</th>
										<th>Date</th>
										<th>Name</th>
										<th>Crime</th>
										<th>Time</th>
										<th>Bail</th>
										<th>Bond</th>
										<th>Arresting Officer</th>
                                        <th>Status</th>
									</tr>
									<?php
									$format = "Y-m-d H:i:s";
									$acnt = count($arrests);
									for($i = 0; $i < $acnt; $i++) {
										$aoffi = getUser($arrests[$i]['copid'], U_ID);
										$utcTS = antiXSS($arrests[$i]['RealDate']);
										$usrTS = date_create($utcTS, new DateTimeZone("UTC")) -> setTimeZone(new DateTimeZone($usrTZ)) -> format($format);
										$exid = $arrests[$i]['id'];
										($arrests[$i]['bondid'] == -1) ? $bond = "No" : $bond = "Yes";
										if($arrests[$i]['bail'] == 0) $bail = "No"; else $bail = "$".number_format($arrests[$i]['bail']);
										echo "<tr><td>".$usrTS."</td><td>".antiXSS($arrests[$i]['date'])."</td><td>$crim</td><td>".titleFormat(antiXSS($arrests[$i]['crimes']))."</td><td>".antiXSS(number_format($arrests[$i]['time']))."</td><td>$bail</td><td>$bond</td><td>$aoffi[display]</td><td><form action=\"expungement_data.php\" method=\"post\"><input type=\"hidden\" value=\"$exid\" name=\"eid\"><input type=\"hidden\" value=\"$crim\" name=\"crim\"><button type=\"submit\" class=\"btn btn-color\"><i class=\"glyphicon glyphicon-send\"></i>Expunge Record</button></form></td></tr>";
									} ?>
								  </tbody>
							  </table>
							  <?php }							  
							  }else echo "This person has no unprocessed arrests!"; ?></center>
                             
                              
							</article>
						</div>
					</div>
				</div>
			</div>
			<?php require_once("sidebar.php"); ?>
				<footer>
					<p class="copyright">&copy; Copyright 2017 <a href="http://coltonbrister.com" target="_blank">Colton Brister</a></p>
				</footer>
			</div>
		</div>

		<!-- JavaScripts -->
		<script type='text/javascript' src='js/jquery.min.js'></script>
		<script type='text/javascript' src='js/bootstrap.min.js'></script>
		<script type='text/javascript' src='js/swiper/idangerous.swiper.min.js'></script>
		<script type='text/javascript' src='js/masonry/masonry.pkgd.min.js'></script>
		<script type='text/javascript' src='js/isotope/jquery.isotope.min.js'></script>
		<script type='text/javascript' src='js/custom.js'></script>
	</body>
</html>