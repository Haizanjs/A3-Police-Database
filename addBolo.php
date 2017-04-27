<!doctype html>
<?php
require_once("php_includes/base.inc.php");
if(!hasPerm("officer")) redirect("index.php");


if(!isset($_SESSION['arrandom'])) $_SESSION['arrandom'] = $_POST['random']+10;

if(isset($_POST['boloInfo']) && !empty($_POST['boloInfo']) && $_POST['random'] != $_SESSION['arrandom']) {
	$bolo = newBolo($_POST['boloInfo']);
	$_SESSION['arrandom'] = $_POST['random'];
}

if(isset($_POST['boid']) && !empty($_POST['boid']) && $_POST['random'] != $_SESSION['arrandom']) {
	$cbolo = cancelBolo($_POST['boid']);
	$_SESSION['arrandom'] = $_POST['random'];
}

if(isset($_POST['history']) && !empty($_POST['history']) && $_POST['random'] != $_SESSION['arrandom']) {
	$histLimit = $_POST['history'];
	$_SESSION['arrandom'] = $_POST['random'];
}else{
	$histLimit = "2";
}

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
		<title><?php echo $cminfo['pda']; ?> - Add BOLO</title>
		<meta name="description" content="<?php echo $cminfo['pdn']; ?> - Add BOLO">
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
								<h1>Add Bolo</h1>
								<h2>Bolo's will expire after 1 hour.</h2>
							</header>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<article class="inner">
								<div class="row">
								  <div class="col-12">
									<h4>View Active/Add Bolo</h4>
									</div>
								</div>
                              <form id="post-comment" class="inner" action="addBolo.php" method="post">
								 <div class="row">
										<div class="form-group col-8">
										  <label for="boloInfo">BOLO Information (keep it short but specific) [IE: Black Escalade with 3 occupants, armed and dangerous.] <span class="form-required" title="This field is required.">*</span></label>
											<input type="text" name="boloInfo" class="form-control" id="boloInfo" required>
								   		</div>
								</div>
                                <button type="submit" class="btn btn-color"><i class="glyphicon glyphicon-send"></i>Add Bolo</button>
							  </form>
							  <?php $ht = ($histLimit > "1") ? "hours" : "hour"; ?>
							  <form id="post-history" class="inner" action="addBolo.php" method="post">
									<div class="row">
										<div class="form-group col-4">
										Show BOLOs from the last <input style="width:50%" type="text" name="history" class="form-control" id="history" placeholder="<?php echo($histLimit); ?>" required> <?php echo($ht); ?>.
                                        <button type="submit" class="btn btn-color"><i class="glyphicon glyphicon-send"></i>Show</button>
										</div>
									</div>
							   </form>
							  <center>
							  <?php
							  $bolos = getBolos($histLimit);
							  if($bolos){
							  ?>
							  <table style="text-align: center">
							  	<tbody>
							  		<tr>
							  			<th>Time Entered</th>
							  			<th>Issuing Officer</th>
							  			<th>Cancelling Officer</th>
							  			<th>BOLO Information</th>
							  			<th>Cancel BOLO</th>
							  		</tr>
							  		<?php
							  		$format = "Y-m-d H:i:s";
									$bcnt = count($bolos);
									for($i = 0; $i < $bcnt; $i++) {
										$ioffi = getUser($bolos[$i]['copid'], U_ID);
										$boid = $bolos[$i]['id'];
										if($bolos[$i]['canceled'] > 0){
											$btnShow = "<span style=\"color:#999\">canceled</span>";
											$doffi = getUser($bolos[$i]['canceled'], U_ID);
											$coffi = $doffi['display'];
										}else{
											$btnShow = "<form id=\"cancel-bolo\" action=\"addBolo.php\" method=\"post\"><input type=\"hidden\" value=\"$boid\" name=\"boid\"><input type=\"hidden\" value=\"$histLimit\" name=\"history\"><button type=\"submit\" class=\"btn btn-color\"><i class=\"glyphicon glyphicon-send\"></i>Cancel Bolo</button></form>";
											$coffi = "none";
										}
										$utcTS = antiXSS($bolos[$i]['RealDate']);
										$usrTS = date_create($utcTS, new DateTimeZone("UTC")) -> setTimeZone(new DateTimeZone($usrTZ)) -> format($format);
										echo "<tr><td>".$usrTS."</td><td>$ioffi[display]</td><td>$coffi</td><td>".titleFormat(antiXSS($bolos[$i]['info']))."</td><td>$btnShow</td></tr>";
									}
							  		?>
							  	</tbody>
							  </table>
							  <?php } else echo "There are no BOLOs to display!"; ?>
							  </center>
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