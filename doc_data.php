<!doctype html>
<?php
require_once("php_includes/base.inc.php");
if(!hasPerm("officer")) redirect("index.php");
if(!isset($_POST['pris'])){redirect("doc.php");}

if(isset($_POST['pris'])){$res = trim($_POST['pris']); $des = "list";}
if(isset($_POST['rid'])){$res = trim($_POST['pris']); $crid = trim($_POST['rid']); $des = "jail";}

if(empty($des)) redirect("doc.php");

$prid = getCiv($res);
if(!$prid) $prid = createCiv(ucwords($res));
$pris = $prid['name'];
sCiv($prid['id']);
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
		<title><?php echo $cminfo['pda']; ?> - Department of Corrections</title>
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
		<link rel="stylesheet" href="css/ticker.css">
		
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
			<?php require_once("boloTicker.php"); ?>
			<div id="content">
				<div class="container-fluid">
					<div id="heading" class="row">
						<div class="col-12">
							<header>
								<h1>Department of Corrections Intake Processing</h1>
								<h2>Mess up a record? Please tell Cole for now.</h2>
							</header>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<article class="inner">
								<div class="row">
								  <div class="col-12">
									<h4>Process Arrests for - <?php echo ucwords($pris); ?></h4>
									</div>
								</div>
								<center>
                            <center>
							  <?php
                              $arrests = getArrests($prid['id'], 1);							  
							  if($arrests && $des == "list"){								  
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
                                        <th>Process Record?</th>
									</tr>
									<?php
									$format = "Y-m-d H:i:s";
									$acnt = count($arrests);
									for($i = 0; $i < $acnt; $i++) {
										$aoffi = getUser($arrests[$i]['copid'], U_ID);
										$utcTS = antiXSS($arrests[$i]['RealDate']);
										$usrTS = date_create($utcTS, new DateTimeZone("UTC")) -> setTimeZone(new DateTimeZone($usrTZ)) -> format($format);
										$crid = $arrests[$i]['id'];
										($arrests[$i]['bondid'] == -1) ? $bond = "No" : $bond = "Yes";
										if($arrests[$i]['bail'] == 0) $bail = "No"; else $bail = "$".number_format($arrests[$i]['bail']);
										echo "<tr><td>".$usrTS."</td><td>".antiXSS($arrests[$i]['date'])."</td><td>$pris</td><td>".titleFormat(antiXSS($arrests[$i]['crimes']))."</td><td>".antiXSS(number_format($arrests[$i]['time']))."</td><td>$bail</td><td>$bond</td><td>$aoffi[display]</td><td><form action=\"doc_data.php\" method=\"post\"><input type=\"hidden\" value=\"$crid\" name=\"rid\"><input type=\"hidden\" value=\"$pris\" name=\"pris\"><button type=\"submit\" class=\"btn btn-color\"><i class=\"glyphicon glyphicon-send\"></i>Process Record</button></form></td></tr>";
									}
									?>
								  </tbody>
							  </table>
							  <?php }else if($des == "jail"){
							  		$updatePris = newIntake($crid);
							  		$arrests = getArrests($crid, 2);
									$acnt = count($arrests);
									for($i = 0; $i < $acnt; $i++) {
										$time = antiXSS(number_format($arrests[$i]['time']));
										$crimes = titleFormat(antiXSS($arrests[$i]['crimes']));
							  		echo "<h5>$pris has been processed, place in jail for <span style=\"color:#ff9933\">$time minutes</span>.</h5>";}
							  ?>
							  	<div id="prisProcessed">
								  <table>
										<tr>
											<td class="clabel">Prisoner:</td>
											<td><?php echo $pris; ?></td>
										</tr>
										<tr>
											<td class="clabel">Time to serve:</td>
											<td><?php echo $time; ?> minutes</td>
										</tr>
										<tr>
											<td class="clabel">Convicted of:</td>
											<td><?php echo $crimes; ?></td>
										</tr>
									</table>
								</div>
							  <?php }else echo "This person has no unprocessed arrests!"; ?></center>
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
		<script type="text/javascript" src="js/ticker.js"></script>
	</body>
</html>