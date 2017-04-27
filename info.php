<!doctype html>
<?php
require_once("php_includes/base.inc.php");
if(!isLoggedIn()){
	echo "You are not logged in... redirecting.";
	redirect("/login.php");
	die();
}

$infol = getInfo("links");
$infol = json_decode($infol['data'], true);
$infot = getInfo("text");
$infot = json_decode($infot['data'], true);
$cminfo = getInfo("cminfo");
$cminfo = json_decode($cminfo['data'], true);

?>
<html lang="en-US">
	<head>

		<!-- Meta -->
		<meta charset="UTF-8">
		<title><?php echo $cminfo['pda']; ?> - General Information</title>
		<meta name="description" content="<?php echo $cminfo['pdn']; ?> - General Information">
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
								<h1>General Information</h1>
							</header>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<article class="inner">
								<div class="row">
									<div class="col-12">
                                        <h5><a href="<?php echo $infol['link1']; ?>" target="_blank"><?php echo $infot['text1']; ?></a></h5>
										<h5><a href="<?php echo $infol['link2']; ?>" target="_blank"><?php echo $infot['text2']; ?></a></h5>
										<h5><a href="<?php echo $infol['link3']; ?>" target="_blank"><?php echo $infot['text3']; ?></a></h5>
										<h5><a href="<?php echo $infol['link4']; ?>" target="_blank"><?php echo $infot['text4']; ?></a></h5>
										<h5><a href="<?php echo $infol['link5']; ?>" target="_blank"><?php echo $infot['text5']; ?></a></h5>
										<h5><a href="<?php echo $infol['link6']; ?>" target="_blank"><?php echo $infot['text6']; ?></a></h5>
										<h5><a href="<?php echo $infol['link7']; ?>" target="_blank"><?php echo $infot['text7']; ?></a></h5>
										<h5><a href="<?php echo $infol['link8']; ?>" target="_blank"><?php echo $infot['text8']; ?></a></h5>
										<h5><a href="<?php echo $infol['link9']; ?>" target="_blank"><?php echo $infot['text9']; ?></a></h5>
										<h5><a href="<?php echo $infol['link10']; ?>" target="_blank"><?php echo $infot['text10']; ?></a></h5>
                                        <h5>Want something added? Let your command know!</h5>
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