<!doctype html>
<?php
require_once("php_includes/base.inc.php");
if(!hasPerm("officer")) redirect("index.php");

if(!isset($_POST['crim'])) redirect("crime.php");

if(isset($_POST['crim'])) $crim = trim($_POST['crim']);

if(empty($crim)) redirect("crime.php");

$crid = getCiv($crim);
if(!$crid) $crid = createCiv(ucwords($crim));
$crim = $crid['name'];
sCiv($crid['id']);

if(empty($_POST['bailbond'])) $_POST['bailbond'] = 0;

if(!isset($_SESSION['arrandom'])) $_SESSION['arrandom'] = $_POST['random']+10;

if(isset($_POST['crime']) && !empty($_POST['crime']) && $_POST['random'] != $_SESSION['arrandom']) {
	$arr = newArrest($crid['id'], $_POST['crime'], $_POST['evi'], $_POST['time'], $_POST['date'], $_POST['ibail'], safeNum($_POST['bailbond']));
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
		<title><?php echo $cminfo['pda']; ?> - Criminal Database</title>
		<meta name="description" content="<?php echo $cminfo['pdn']; ?> - Criminal Information">
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
								<h1>View Criminal Search History</h1>
								<h2>Mess up a crime? Please tell Cole for now.</h2>
							</header>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<div class="alert alert-warning fade in">
												<i class="fa fa-exclamation-triangle"></i>
												<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
												<p>Please read this regarding the future of the police database, <a href="notice.php">HERE</a></p>
											</div>
							<article class="inner">
								<div class="row">
									<div class="col-12">
                                    <?php 
										$totalArr = getArrests($crid['id']); $numerArr = count($totalArr);
										$totalWrr = getWarrants($crid['id']); $numerWrr = count($totalWrr);
									?>
										<h4>View/Add Criminal Record - <?php echo ucwords($crim); if(isCop($crim)) echo " <span style=\"color:red\">(OFFICER)</span>"; ?> (<strong><?php if($totalArr){echo $numerArr;}else echo "0";?></strong> Priors)
										<br>
											<strong><span style=\"color:red\"><?php if($totalWrr){echo " <span style=\"color:red\">$numerWrr Active Warrant</span> - <a href=\"warrants.php\">View Warrants</a>";}else echo " <span style=\"color:green\">0 Active Warrants</span>";?></strong>
										</h4>
									</div>
								</div>
                              <form id="post-comment" class="inner" action="crime_data.php" method="post">
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
											<label for="name">Suspect Name <span class="form-required" title="This field is required.">*</span></label>
											<input autocomplete="off" list="autocomp" type="text" name="crim" class="form-control" value="<?php echo $crim; ?>" id="name">
											<datalist id="autocomp"></datalist>
										</div>
										<div class="form-group col-2">
										  <label for="time">Total Time <span class="form-required" title="This field is required.">*</span></label>
											<input type="text" name="time" placeholder="In minutes" class="form-control" id="time" required >
											<input type="hidden" name="random" value="<?php echo rand(); ?>">
						   		  		</div>
                                        <div class="form-group col-2">
										  <label for="date">Date <span class="form-required" title="This field is required.">*</span></label>
											<input type="text" name="date" required value="<?php echo date("Y-m-d"); ?>" placeholder="yyyy-mm-dd" class="form-control" id="date">
								   		</div>
								   		<div class="form-group col-1">
								   		  
								   		    <label>
								   		      <input type="radio" checked name="ibail" value="Bail" id="RadioGroup1_0" class="form-control">
								   		      Bail</label>
								   		    <label>
								   		      <input name="ibail" type="radio" id="RadioGroup1_1" value="Bond" class="form-control">
								   		      Bond</label>
								   		    <br>
							   		      
                                   </div>
                                   		<div class="form-group col-2">
										  <label for="bond">Bail/Bond</label>
											<input type="text" name="bailbond" placeholder="If none leave blank" class="form-control" id="bond" >
						   		   		</div>
                                        <div class="form-group col-4">
										  <label for="crime">Crime(s) <span class="form-required" title="This field is required.">*</span></label>
											<input type="text" name="crime" placeholder="Seperate each crime with a ," class="form-control" id="crime" required>
								   		</div>
										<div class="form-group col-8">
										  <label for="evi">Evidence (Please keep links short, use <a href="https://goo.gl/">https://goo.gl</a> if needed. If posting pictures, please link to a <a href="http://imgur.com/">Imgur</a> album)</label>
											<input type="text" name="evi" placeholder="Provide any evidence if needed" class="form-control" id="evi">
								   		</div>
								</div>
                                <button type="submit" class="btn btn-color"><i class="glyphicon glyphicon-send"></i>Add Record</button>
							  </form><center>
							  <?php
							  $arrests = getArrests($crid['id']);
							  if($arrests) {
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
										<th>Evidence</th>
										<th>Arresting Officer</th>
                                        <th>Processing Officer</th>
									</tr>
									<?php
									$format = "Y-m-d H:i:s";
									$acnt = count($arrests);
									for($i = 0; $i < $acnt; $i++) {
										$aoffi = getUser($arrests[$i]['copid'], U_ID);
										$tproc = getUser($arrests[$i]['docid'], U_ID);
										$utcTS = antiXSS($arrests[$i]['RealDate']);
										$usrTS = date_create($utcTS, new DateTimeZone("UTC")) -> setTimeZone(new DateTimeZone($usrTZ)) -> format($format);
										($arrests[$i]['proc'] == 0) ? $poffi = "<span style='color:#f00'>Not Processed</span>" : $poffi = $tproc[display];
										($arrests[$i]['bondid'] == -1) ? $bond = "No" : $bond = "Yes";
										if($arrests[$i]['bail'] == 0) $bail = "No"; else $bail = "$".number_format($arrests[$i]['bail']);
										echo "<tr><td>".$usrTS."</td><td>".antiXSS($arrests[$i]['date'])."</td><td>$crim</td><td>".titleFormat(antiXSS($arrests[$i]['crimes']))."</td><td>".antiXSS(number_format($arrests[$i]['time']))."</td><td>$bail</td><td>$bond</td><td>".titleFormat(antiXSS($arrests[$i]['evd']))."</td><td>$aoffi[display]</td><td>$poffi</td></tr>";
									}
									?>
								  </tbody>
							  </table><?php } else echo "This person has no arrests!"; ?></center>
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