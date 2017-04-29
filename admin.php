<!doctype html>
<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
require_once("php_includes/base.inc.php");
if(!hasPerm("all")){
	redirect("/index.php");
	die();
}

$info = getInfo("cminfo");
$info = json_decode($info['data'], true);

if(isset($_POST['cmn'])) {
	if(!empty($_POST['cmn']) && $info['cminfo'] != $_POST['cmn']) {
		setInfo("cminfo", json_encode($_POST));
		$info['cminfo'] = $_POST['cmn'];
	}
}

?>

<html lang="en-US">
	<head>

		<!-- Meta -->
		<meta charset="UTF-8">
		<title><?php echo $info['pda']; ?> - Admin Dashboard</title>
        <script src="js/jquery-2.1.4.min.js"></script>
		<script src="js/Chart.js"></script>
		<meta name="description" content="<?php echo $info['pdn']; ?> - Admin Dashboard">
		<meta name="author" content="Cole Banks, Scott Harm (Retired)">
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
								<h1>Administrator Panel</h1>
							</header>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<article class="inner">
								<div class="row">
                               		<form action="admin.php" method="post">
                                    <div class="form-group col-12" style="border:double">
										<h3>Database Settings</h3>
										<div class="form-group col-3">
											<label for="cmn" title="This will appear on the welcome page">Community Name: [<strong>?</strong>]</label>
												<input type="text" name="cmn" value="<?php echo $info['cmn']; ?>" class="form-control" id="cmn" style="width:100%">
										</div>
										<div class="form-group col-3">
											<label for="pdn" title="Example: New York City Police Department">Police Department Name: [<strong>?</strong>]</label>
												<input type="text" name="pdn" value="<?php echo $info['pdn']; ?>" class="form-control" id="pdn" style="width:100%">
										</div>
										<div class="form-group col-3">
											<label for="pda" title="Example: NYPD">Police Department Abbreviation: [<strong>?</strong>]</label>
												<input type="text" name="pda" value="<?php echo $info['pda']; ?>" class="form-control" id="pda" style="width:100%">
										</div>
										<div class="form-group col-3">
											<label for="cn" title="Why should officers contact if they find an issue with the database?">Contact Name: [<strong>?</strong>]</label>
												<input type="text" name="cn" value="<?php echo $info['cn']; ?>" class="form-control" id="cn" style="width:100%">
										</div>
										<div class="form-group col-12">
											<button type="submit" class="btn btn-color"><i class="glyphicon glyphicon-send"></i>Submit Changes</button>
										</div>
									</div>
                                    </form>
									<div class="form-group col-12" style="border:double">
										<h3>Need Help or want something custom?</h3>
										<p>While I may not be continuing work on this project, I still help when needed. If you have a question or need to report a bug, feel free to shoot me an email. If you’re interested in having a custom feature made, feel free to email me as well, of course though there is a price to be paid for custom work.<br><strong>contact@coltonbrister.com</strong><br>Please also make sure you read the <a href="https://github.com/ColeB97/A3-Police-Database/wiki" target="_blank">Documentation</a></p>
									</div>
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