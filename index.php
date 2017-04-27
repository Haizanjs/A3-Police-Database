<!doctype html>
<?php
require_once("php_includes/base.inc.php");
if(!isLoggedIn()){
	echo "You are not logged in... redirecting.";
	redirect("login.php");
	die();
}
$usr = getUser($_SESSION['uname'], U_UNAME);
$cminfo = getInfo("cminfo");
$cminfo = json_decode($cminfo['data'], true);

if($usr['dept'] == PENDING) {
	redirect("settings.php");
}
?>
<html lang="en-US">
	<head>

		<!-- Meta -->
		<meta charset="UTF-8">
		<title><?php echo $cminfo['pdn']; ?> Database</title>
		<meta name="description" content="<?php echo $cminfo['pdn']; ?> - Criminal Database Homepage">
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

  		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
		<script type="text/javascript" src="js/tzselect.js"></script>
		<script type="text/javascript">
			$(document).ready(function () {
			    var timezone = jstz.determine();
			    response_text = 'UTC';
			    if (typeof (timezone) === 'undefined'){
			        response_text = 'UTC';
			    }else{
			        response_text = timezone.name();
			    }
			    document.cookie = "LSTZ=" + response_text;
			});
		</script>
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
				<div id="fullscreen-slider" class="swiper-container">
					<div class="swiper-wrapper">
						<div class="swiper-slide overlay overlay-dark-25 white" style="background-image: url(img/slides/front.png)">
							<h1>Welcome to the <?php echo $cminfo['cmn']; ?> police database!<br>Build Version 1.1.2</h1>
							<?php
							if($usr['dept'] != -1) {
								$dname = explode(" ", $usr['display']);
								$ln = count('dname');
								echo "<br/><h2 style=\"color: black\">Welcome, ".getRankName($usr['id'])." ".$dname[$ln];
							}
							?>
							
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