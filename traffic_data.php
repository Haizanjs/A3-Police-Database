<!doctype html>
<?php
require_once("php_includes/base.inc.php");
if(!hasPerm("officer")) redirect("index.php");
if(!isset($_POST['trac']) && !isset($_POST['trac'])) redirect("traffic.php");
if(isset($_POST['trac'])) $_POST['name'] = $_POST['trac'];

$traf = "No One";
if(isset($_POST['trac']) && !empty($_POST['trac'])) $traf = trim($_POST['trac']);

if(empty($traf) && !isset($_POST['name'])) redirect("traffic.php");

$trac = getCiv($traf);
if(!$trac) $trac = createCiv(ucwords($traf));
$traf = $trac['name'];
sCiv($trac['id']);

if(empty($_POST['bailbond'])) $_POST['bailbond'] = 0;

if(!isset($_SESSION['arrandom'])) $_SESSION['arrandom'] = $_POST['random']+10;
if(isset($_POST['ticket']) && !empty($_POST['ticket']) && $_POST['random'] != $_SESSION['arrandom']) {
	$trr = newTraffic($trac['id'], $_POST['ticket'], $_POST['date'], safeNum($_POST['price']), $_POST['notes']);
	$_SESSION['arrandom'] = $_POST['random'];
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
		<title><?php echo $cminfo['pda']; ?> - Infractions</title>
		<meta name="description" content="<?php echo $cminfo['pdn']; ?> - Infractions">
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
								<h1>View Offender Search History</h1>
								<h2>Mess up a entry? Please tell Cole for now.</h2>
							</header>

						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<article class="inner">
								<div class="row">
								  <div class="col-12">
									<h4>View/Add Offender Record - <?php echo ucwords($traf); if(isCop($traf)) echo " <span style=\"color:red\">(OFFICER)</span>"; ?></h4>
									</div>
								</div>
                               
                              <form id="post-comment" class="inner" action="traffic_data.php" method="post">
								 <div class="row">
										<div class="form-group col-2">
										<script type="text/javascript">
												var lastVal = "";
												function autocomp() {
													var cVal = document.getElementById("name").value;
													if(cVal.length >= 2 && lastVal != cVal) {
														$.get( "autocomplete.php?name="+document.getElementById("name").value, function( data ) {
															document.getElementById("autocomp").innerHTML = data;
														});
														lastVal = cVal;
													}
												}
												setInterval("autocomp()", 2000);
											</script>
											<label for="name">Offender Name <span class="form-required" title="This field is required.">*</span></label>
											<input autocomplete="off" list="autocomp" type="text" name="trac" class="form-control" value="<?php echo $traf; ?>" id="name">
											<datalist id="autocomp"></datalist>
										</div>
                                        <div class="form-group col-2">
										  <label for="date">Date <span class="form-required" title="This field is required.">*</span></label>
											<input type="text" name="date" required value="<?php echo date("Y-m-d"); ?>" placeholder="yyyy-mm-dd" class="form-control" id="date">
								   		</div>
								   		<div class="form-group col-2">
										  <label for="ticket">Ticket Reason</label>
											<input type="text" name="ticket" class="form-control" id="ticket" required >
						   		   		</div>
								   		<div class="form-group col-2">
										  <label for="time">Ticket Price </label>
											<input type="text" placeholder="If warning leave blank" name="price" class="form-control" id="price" >
											<input type="hidden" name="random" value="<?php echo rand(); ?>">
						   		  		</div>
								   		<div class="form-group col-8">
                                        	<label for="extraInfo">Additional Information </label>
                                            	<textarea style="width:100%" name="notes" class="form-control" id="extrInfo"></textarea>
                                        </div>
								</div>
                                <button type="submit" class="btn btn-color"><i class="glyphicon glyphicon-send"></i>Add Offense</button>
							  </form><center>
							  <?php
							  
							  $traffic = getTraffic($trac['id']);
							  if($traffic) {
							  ?>
							  <table style="text-align: center">
								  <tbody>
									<tr>
										<th>Time Entered</th>
										<th>Date</th>
										<th>Name</th>
										<th>Offense</th>
										<th>Ticket Price</th>
										<th>Officer</th>
										<th>Additional Information</th>
									</tr>
									<?php
									$format = "Y-m-d H:i:s";
									$acnt = count($traffic);
									for($i = 0; $i < $acnt; $i++) {
										$aoffi = getUser($traffic[$i]['copid'], U_ID);
										$utcTS = antiXSS($traffic[$i]['realdate']);
										$usrTS = date_create($utcTS, new DateTimeZone("UTC")) -> setTimeZone(new DateTimeZone($usrTZ)) -> format($format);
										if($traffic[$i]['ticket'] == 0) $ticket = "N/A (Warning only)"; else $ticket = "$".number_format($traffic[$i]['ticket']);
										if(empty($traffic[$i]['notes'])) $traffic[$i]['notes'] = "N/A";
										echo "<tr><td>".$usrTS."</td><td>".antiXSS($traffic[$i]['date'])."</td><td>$traf</td><td>".titleFormat(antiXSS($traffic[$i]['reason']))."</td><td>".antiXSS($ticket)."</td><td>$aoffi[display]</td><td>".$traffic[$i]['notes']."</td></tr>";
									}
									?>
								  </tbody>
							  </table><?php } else echo "This person has no traffic offenses!"; ?></center>
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