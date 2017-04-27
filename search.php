<!doctype html>
<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
require_once("php_includes/base.inc.php");
if(!hasPerm("pdcmd")){
	redirect("/index.php");
	die();
}

if(!isset($_POST['copm'])) redirect("dashboard.php");
if(isset($_POST['copm'])) $copm = trim($_POST['copm']);
if(empty($copm)) redirect("dashboard.php");
$copd = getUser($copm);
if(!$copd) $copd = redirect("/searchf.php");

$usr = getUser($copd['uname'], U_UNAME);

$stmt = $pdo->prepare("SELECT * FROM `arrests` WHERE `copid` = '$copd[id]' ORDER BY `id` DESC");
$stmt->execute();
$arrests = $stmt->fetchAll();
$acnt = count($arrests);

$stmt = $pdo->prepare("SELECT * FROM `traffic` WHERE `copid` = '$copd[id]' ORDER BY `id` DESC");
$stmt->execute();
$traffic = $stmt->fetchAll();
$tcnt = count($traffic);

if(isset($_COOKIE["LSTZ"])){
	$usrTZ = htmlspecialchars($_COOKIE["LSTZ"]);
}else{
	$usrTZ = "UTC";
}

$cminfo = getInfo("cminfo");
$cminfo = json_decode($cminfo['data'], true);
?>

<html lang="en-US">
	<head>
		<!-- Meta -->
		<meta charset="UTF-8">
		<title><?php echo $cminfo['pda']; ?> - Officer Search</title>
        <script src="js/jquery-2.1.4.min.js"></script>
		<script src="js/Chart.js"></script>
		<meta name="description" content="<?php echo $cminfo['pdn']; ?> - Officer Search">
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
		<link rel="stylesheet" href="css/ticker.css">

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
			<?php require_once("boloTicker.php"); ?>
			<div id="content">
				<div class="container-fluid">
					<div id="heading" class="row">
						<div class="col-12">
							<header>
								<h1>Results for - <?php  echo " <span style=\"color:red\">".getRankName($usr['id']).", ".$copm."</span>"; ?></h1>
							</header>
						</div>
					</div>
					<div class="row">
						<div class="main-column col-12">
							<div class="inner">
								<div class="row" id="accordion">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title">
												<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="collapsed">Arrest History</a>
											</h4>
										</div>
										<div id="collapseOne" class="panel-collapse collapse">
											<div class="panel-body"><center>
												<?php if($arrests) { ?>
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
															<th>Evidence</th>
														</tr>
														<?php
														$format = "Y-m-d H:i:s";
														$acnt = count($arrests);
														for($i = 0; $i < $acnt; $i++) {
															$aoffi = getUser($arrests[$i]['copid'], U_ID);
															$tproc = getUser($arrests[$i]['docid'], U_ID);
															$utcTS = antiXSS($arrests[$i]['RealDate']);
															$cname = getCiv($arrests[$i]['uid'], U_ID);
															$usrTS = date_create($utcTS, new DateTimeZone("UTC")) -> setTimeZone(new DateTimeZone($usrTZ)) -> format($format);
															($arrests[$i]['bondid'] == -1) ? $bond = "No" : $bond = "Yes";
															if($arrests[$i]['bail'] == 0) $bail = "No"; else $bail = "$".number_format($arrests[$i]['bail']);
															echo "<tr><td>".$usrTS."</td><td>".antiXSS($arrests[$i]['date'])."</td><td>".antiXSS($cname['name'])."</td><td>".titleFormat(antiXSS($arrests[$i]['crimes']))."</td><td>".antiXSS(number_format($arrests[$i]['time']))."</td><td>$bail</td><td>$bond</td><td>".titleFormat(antiXSS($arrests[$i]['evd']))."</td></tr>";
														}
														?>
													</tbody>
												</table>
												<?php } else echo "No Arrests Found on File"; ?></center>
											</div>
										</div>
									</div>
								</div>
								<div class="row" id="accordion">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title">
												<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed">Citation History</a>
											</h4>
										</div>
										<div id="collapseTwo" class="panel-collapse collapse">
											<div class="panel-body"><center>
												<?php if($traffic) { ?>
												<table style="text-align: center">
													<tbody>
														<tr>
															<th>Time Entered</th>
															<th>Date</th>
															<th>Name</th>
															<th>Offense</th>
															<th>Ticket Price</th>
															<th>Additional Information</th>
														</tr>
														<?php
														for($i = 0; $i < $tcnt; $i++) {
															$civ = getCiv($traffic[$i]['civid'], U_ID);
															$traf = $civ['name'];
															if($traffic[$i]['ticket'] == 0) $ticket = "N/A (Warning only)"; else $ticket = "$".number_format($traffic[$i]['ticket']);
															if(empty($traffic[$i]['notes'])) $traffic[$i]['notes'] = "N/A";
															echo "<tr><td>".antiXSS($traffic[$i]['realdate'])."</td><td>".antiXSS($traffic[$i]['date'])."</td><td>$traf</td><td>".titleFormat(antiXSS($traffic[$i]['reason']))."</td><td>".antiXSS($ticket)."</td><td>".$traffic[$i]['notes']."</td></tr>";
														}
														?>
													</tbody>
												</table>
												<?php } else echo "No Citations Found on File"; ?></center>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php require_once("sidebar.php"); ?>
				<footer>
					<p class="copyright">&copy; Copyright 2017 <a href="http://coltonbrister.com" target="_blank">Colton Brister</a></p>
				</footer>
		</div>

		<!-- JavaScripts -->
		<script type='text/javascript' src='js/jquery.min.js'></script>
		<script type='text/javascript' src='js/bootstrap.min.js'></script>
		<script type='text/javascript' src='js/swiper/idangerous.swiper.min.js'></script>
		<script type='text/javascript' src='js/masonry/masonry.pkgd.min.js'></script>
		<script type='text/javascript' src='js/isotope/jquery.isotope.min.js'></script>
		<script type='text/javascript' src='js/custom.js'></script>
		<script type="text/javascript" src="js/ticker.js"></script>

	</body>
</html>