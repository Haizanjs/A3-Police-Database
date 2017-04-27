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

if(isset($_POST['emg'])) {
	$t['level'] = $_POST['threat'];
	setInfo("threat", json_encode($t));
	unset($_POST['threat']);
	setInfo("freqs", json_encode($_POST));
}

$infol = getInfo("links");
$infol = json_decode($infol['data'], true);
$infot = getInfo("text");
$infot = json_decode($infot['data'], true);
$info = getInfo("freqs");
$info = json_decode($info['data'], true);
$tinfo = getInfo("threat");
$tinfo = json_decode($tinfo['data'], true);
$stmt = $pdo->prepare("SELECT * FROM `arrests` WHERE `RealDate` > NOW() - INTERVAL 24 HOUR ORDER BY `id` DESC");
$stmt->execute();
$arrests = $stmt->fetchAll();
$acnt = count($arrests);
$stmt->closeCursor();
$stmt = $pdo->prepare("SELECT * FROM `traffic`");
$stmt->execute();
$infs = $stmt->fetchAll();
$infracs = count($infs);
$cminfo = getInfo("cminfo");
$cminfo = json_decode($cminfo['data'], true);

$dtu = hasPerm("dtu");
$cmd = hasPerm("pdcmd");

$stmtsTotal = $pdo->prepare("SELECT `id` FROM `arrests`");
$stmtsTotal->execute();
$arrestsTotal = $stmtsTotal->fetchAll();
$acntTotal = count($arrestsTotal);

$stmtspendTotal = $pdo->prepare("SELECT `id` FROM `requests`");
$stmtspendTotal->execute();
$pendingplace = $stmtspendTotal->fetchAll();
$pndplcTotal = count($pendingplace);

$cnt = count(copsByDept(PENDING))-1;

if(isset($_POST['text1'])) {
	if(!empty($_POST['text1']) && $infot['text'] != $_POST['text1']) {
		setInfo("text", json_encode($_POST));
		$infot['text'] = $_POST['text1'];
	}
if(isset($_POST['link1'])) 
	if(!empty($_POST['link1']) && $infol['links'] != $_POST['link1']) {
		setInfo("links", json_encode($_POST));
		$infol['links'] = $_POST['link1'];
	}
}
?>

<html lang="en-US">
	<head>

		<!-- Meta -->
		<meta charset="UTF-8">
		<title><?php echo $cminfo['pda']; ?> - Command Dashboard</title>
        <script src="js/jquery-2.1.4.min.js"></script>
		<script src="js/Chart.js"></script>
		<meta name="description" content="<?php echo $cminfo['pdn']; ?> - Command Dashboard">
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
								<h1>Command Dashboard</h1>
							</header>

						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<article class="inner">
								<div class="row">
                               		<form action="dashboard.php" method="post">
                                    <div class="form-group col-6" style="border:double">
                                	<div class="form-group col-4">
                                    	<h3>Police Freqs</h3>
                                    	<label for="fr1">Frequency 1:</label>
											<input type="text" name="tac1" value="<?php echo $info['tac1']; ?>" class="form-control" id="fr1" style="width:75%">
										<label for="fr2">Frequency 2:</label>
											<input type="text" name="tac2" value="<?php echo $info['tac2']; ?>" class="form-control" id="fr2" style="width:75%">
                                        <label for="doc">DOC Frequency:</label>
											<input type="text" name="doc1" value="<?php echo $info['doc1']; ?>" class="form-control" id="doc" style="width:75%">
										<?php if(hasPerm("dtu")) { ?>
										<label for="dtu">DTU Frequency:</label>
											<input type="text" name="dtu" value="<?php  echo $info['dtu']; ?>" class="form-control" id="dtu" style="width:75%"> 
										<?php } ?>
                                        <label for="fr3">Emergency:</label>
											<input type="text" style="width:75%" id="fr3" class="form-control" value="<?php echo $info['emg']; ?>" name="emg">
                                        <div class="styled-select">
                                        <label>Active Frequency:</label>
                                        <select style="width: 200px" name="active">
												<option value="tac1" <?php if($info['active'] == "tac1") echo "selected"; ?>>Tac 1</option>
												<option value="tac2" <?php if($info['active'] == "tac2") echo "selected"; ?>>Tac 2</option>
												<option value="emg" <?php if($info['active'] == "emg") echo "selected"; ?>>Emergency Backup</option>
										</select>
                                        </div>
                                     </div>
                                     <div class="form-group col-4">
                                     	<h3>EMS Freqs</h3>
                                     		<label for="emsps">EMS-PD Joint:</label>
												<input type="text" name="ems" value="<?php echo $info['ems']; ?>" class="form-control" id="emspd" style="width:75%">
                                     		<label for="ems">CH-3:</label>
												<input type="text" name="ch3" value="<?php echo $info['ch3']; ?>" class="form-control" id="ems" style="width:75%">
                                        <div class="styled-select">
										<label>Active Threat Level:</label>
                                        <select style="width: 200px" name="threat">
												<option value="1" <?php if($tinfo['level'] == "1") echo "selected"; ?>>Green</option>
												<option value="2" <?php if($tinfo['level'] == "2") echo "selected"; ?>>Amber</option>
												<option value="3" <?php if($tinfo['level'] == "3") echo "selected"; ?>>Red</option>
												<option value="4" <?php if($tinfo['level'] == "4") echo "selected"; ?>>Martial Law</option>
										</select>
                                        </div>
                                        <br/>
                                         	<button type="submit" class="btn btn-color"><i class="glyphicon glyphicon-send"></i>Submit Frequencies</button>
                                    </div>
									<div class="form-group col-4">
                                     	<h3>Freq Generator</h3>
                                        	<label for="randFreq">Frequency Generater:</label>
												<input type="text" style="width:60%" id="randfreq" class="form-control" onclick="this.focus(); this.select();">
                                                <br>
                                         	<button class="btn btn-color" type="button" onclick="var freq = Math.random() * 10000 % 52 +34; freq = freq.toString().substring(0,4); document.getElementById('randfreq').value = freq; document.getElementById('randfreq').focus(); document.getElementById('randfreq').select();"><i class="glyphicon glyphicon-send"></i>Generate</button>
                                         	
                                    </div>
                                    </form>
                                    </div>
                                    <div class="form-group col-6" style="border:double">
                                    <div class="form-group col-6">
                                    	<h3>Database Overview</h3>
                                        <font size="+1">
                                        	<label>Officers requesting Transfer/Placement: <strong><?php echo $pndplcTotal; ?></strong></label>
                                            <br>
                                            <label>Total Arrests in Last 24h: <strong><?php echo $acnt; ?></strong></label>
                                            <br>
                                            <label>Total Arrests: <strong><?php echo $acntTotal; ?></strong></label>
                                            <br>
                                            <label>Total Infractions: <strong> <?php echo $infracs; ?> </strong></label>
                                         </font>
                                    </div>
									<div class="form-group col-6">
										<h3>Officer Search</h3>
										<form action="search.php" method="post">
											<label for="name">Name <span class="form-required" title="This field is required.">*</span></label>
												<input type="text" name="copm" id="copm" class="form-control" required><br>
											<button type="submit" class="btn btn-color"><i class="glyphicon glyphicon-send"></i>Search Officer</button>
										</form>
                                    </div>
                                    </div>
									<form action="dashboard.php" method="post">
                                    	<div class="form-group col-12" style="border:double">
											<h3>Useful Info Links (Blank = nothing will be shown)</h3>
											<table style="text-align: center">
												<tr>
													<th>Link Text</th>
													<th>Page Link</th>
												</tr>
												<tr>
													<td><input type="text" name="text1" value="<?php echo $infot['text1']; ?>" class="form-control" id="text1"></td>
													<td><input type="text" name="link1" value="<?php echo $infol['link1']; ?>" class="form-control" id="link1"></td>
												</tr>
												<tr>
													<td><input type="text" name="text2" value="<?php echo $infot['text2']; ?>" class="form-control" id="text2"></td>
													<td><input type="text" name="link2" value="<?php echo $infol['link2']; ?>" class="form-control" id="link2"></td>
												</tr>
												<tr>
													<td><input type="text" name="text3" value="<?php echo $infot['text3']; ?>" class="form-control" id="text3"></td>
													<td><input type="text" name="link3" value="<?php echo $infol['link3']; ?>" class="form-control" id="link3"></td>
												</tr>
												<tr>
													<td><input type="text" name="text4" value="<?php echo $infot['text4']; ?>" class="form-control" id="text4"></td>
													<td><input type="text" name="link4" value="<?php echo $infol['link4']; ?>" class="form-control" id="link4"></td>
												</tr>
												<tr>
													<td><input type="text" name="text5" value="<?php echo $infot['text5']; ?>" class="form-control" id="text5"></td>
													<td><input type="text" name="link5" value="<?php echo $infol['link5']; ?>" class="form-control" id="link5"></td>
												</tr>
												<tr>
													<td><input type="text" name="text6" value="<?php echo $infot['text6']; ?>" class="form-control" id="text6"></td>
													<td><input type="text" name="link6" value="<?php echo $infol['link6']; ?>" class="form-control" id="link6"></td>
												</tr>
												<tr>
													<td><input type="text" name="text7" value="<?php echo $infot['text7']; ?>" class="form-control" id="text7"></td>
													<td><input type="text" name="link7" value="<?php echo $infol['link7']; ?>" class="form-control" id="link7"></td>
												</tr>
												<tr>
													<td><input type="text" name="text8" value="<?php echo $infot['text8']; ?>" class="form-control" id="text8"></td>
													<td><input type="text" name="link8" value="<?php echo $infol['link8']; ?>" class="form-control" id="link8"></td>
												</tr>
												<tr>
													<td><input type="text" name="text9" value="<?php echo $infot['text9']; ?>" class="form-control" id="text9"></td>
													<td><input type="text" name="link9" value="<?php echo $infol['link9']; ?>" class="form-control" id="link9"></td>
												</tr>
												<tr>
													<td><input type="text" name="text10" value="<?php echo $infot['text10']; ?>" class="form-control" id="text10"></td>
													<td><input type="text" name="link10" value="<?php echo $infol['link10']; ?>" class="form-control" id="link10"></td>
												</tr>
											</table>
											<button type="submit" class="btn btn-color"><i class="glyphicon glyphicon-send"></i>Submit Links</button>
											<br><br>
                                    	</div>
                                	</form>
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