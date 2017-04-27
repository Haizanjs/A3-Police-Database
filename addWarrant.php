<!doctype html>
<?php
require_once("php_includes/base.inc.php");
if(!hasPerm("officer")){
	redirect("/index.php");
	die();
}

if(!isset($_POST['crim'])) redirect("wname.php");

if(isset($_POST['crim'])) $crim = trim($_POST['crim']);
if(isset($_POST['eid'])){$res = trim($_POST['crim']); $exid = trim($_POST['eid']); $des = "served";}

if(empty($crim)) redirect("wname.php");

$crid = getCiv($crim);
if(!$crid) $crid = createCiv(ucwords($crim));
$crim = $crid['name'];
sCiv($crid['id']);

if(!isset($_SESSION['arrandom'])) $_SESSION['arrandom'] = $_POST['random']+10;

if(isset($_POST['warrant']) && !empty($_POST['warrant']) && $_POST['random'] != $_SESSION['arrandom']) {
	$wrr = newWarrant($crid['id'], $_POST['dojname'], $_POST['warrant'], $_POST['wtype'], $_POST['date'], $_POST['wlink']);
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
		<title><?php echo $cminfo['pda']; ?> - Search/Add Warrants</title>
		<meta name="description" content="<?php echo $cminfo['pdn']; ?> - Search/Add Warrants">
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
			<div id="content">
				<div class="container-fluid">
					<div id="heading" class="row">
						<div class="col-12">
							<header>
								<h1>Search/Add Warrants</h1>
								<h2>Only DOJ will be able to add warrants.</h2>
							</header>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<article class="inner">
								<div class="row">
									<?php
									if (hasPerm("doj")) {
									?>
									<form id="post-comment" class="inner" action="addWarrant.php" method="post">
									<div class="form-group col-5" style="border:double">
                                	<div class="form-group col-12">
                                    	<h3>Add Warrant</h3>
                                    	<label for="crim">Suspect Name: <span class="form-required" title="This field is required.">*</span></label>
											<input type="text" name="crim" value="<?php echo $crim; ?>" class="form-control" id="crim" style="width:50%" required>
                                        <label for="dojname">Approving Judge Name: <span class="form-required" title="This field is required.">*</span></label>
											<input type="text" name="dojname" value="" class="form-control" id="dojname" style="width:50%" required>
                                        <label for="warrant">Crime(s) <span class="form-required" title="This field is required.">*</span></label>
                                        	<input type="text" name="warrant" placeholder="Seperate each crime with a ," class="form-control" id="warrant" style="width:80%" required>
                                        <label for="wtype">Warrant Type: <span class="form-required" title="This field is required.">*</span></label>
                                        <select class="form-control" style="width: 50%" name="wtype">
												<option value="Arrest">Arrest</option>
												<option value="Bench">Bench</option>
												<option value="Search and Seizure">Search and Seizure</option>
										</select>
                                        <label for="date">Date of Affidavit: <span class="form-required" title="This field is required.">*</span></label>
											<input type="text" name="date" required value="<?php echo date("Y-m-d"); ?>" placeholder="yyyy-mm-dd" class="form-control" id="date" style="width:25%" required>
                                        <label for="wlink">Warrant Link: <span class="form-required" title="This field is required.">*</span></label>
                                        	<input type="text" name="wlink" class="form-control" id="wlink" style="width:100%" required></input>
                                     <br>
                                     <button type="submit" class="btn btn-color"><i class="glyphicon glyphicon-send"></i>Add Warrant</button>
                                     </div>
                                    </form>
                                    </div>
									<div class="col-12"><br><center>
										<?php
										}
										$warrants = getWarrants($crid['id']);
										if($warrants) {
											if($des == "served"){
											$updateWar = updateWarrant($_POST['eid']);
											echo "<h5>".$crim."'s warrant has been served.</h5>";
										}
										?>
												<table style="text-align: center">
												<tbody>
												<tr>
													<th>Name</th>
													<th>Approving Judge</th>
													<th>Crimes</th>
													<th>Type</th>
													<th>Date</th>
													<th>Link</th>
													<th>Serve</th>
												</tr>
												<?php
												$format = "Y-m-d H:i:s";
												$acnt = count($warrants);
												for($i = 0; $i < $acnt; $i++) {
													$cname = getCiv($warrants[$i]['uid'], U_ID);
													$exid = $warrants[$i]['id'];
													echo "
													<tr><td style=\"width:15%\"\>".antiXSS($cname['name'])."</td><td style=\"width:15%\">".antiXSS($warrants[$i]['dojname'])."</td><td style=\"width:30%\">".titleFormat(antiXSS($warrants[$i]['crimes']))."</td><td style=\"width:10%\">".antiXSS($warrants[$i]['wtype'])."</td><td style=\"width:10%\">".antiXSS($warrants[$i]['date'])."</td><td style=\"width:10%\">".("<a href=".$warrants[$i]['wlink']." target=\"_blank\">WARRANT LINK</a>")."</td>
													
													<td><form action=\"addWarrant.php\" method=\"post\"><input type=\"hidden\" value=\"$exid\" name=\"eid\"><input type=\"hidden\" value=\"$crim\" name=\"crim\"><button type=\"submit\" class=\"btn btn-color\"><i class=\"glyphicon glyphicon-send\"></i>Serve Warrant</button></form></td>
													
													</tr>";
												}
												?>
											</tbody>
											</table>
											<?php } else echo "This person has no active warrants!"; ?></center>
									</div>
								</div>
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