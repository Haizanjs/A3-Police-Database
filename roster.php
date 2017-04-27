<!doctype html>
<?php
require_once("php_includes/base.inc.php");
if(!isLoggedIn()){
	echo "You are not logged in... redirecting.";
	redirect("/login.php");
	die();
}

$cminfo = getInfo("cminfo");
$cminfo = json_decode($cminfo['data'], true);
?>
<html lang="en-US">
	<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

		<!-- Meta -->
		<title><?php echo $cminfo['pda']; ?> - Roster</title>
		<meta name="description" content="<?php echo $cminfo['pdn']; ?> - Roster">
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
								<h1>Metropolis Law Enforcement Roster</h1>
							</header>

						</div>
					</div>
					<div class="row">
						<div class="col-12">
                        <div class="alert alert-info fade in">
										<i class="fa fa-info-circle"></i>
										<p>If something is wrong here contact your command. If no one in your command can help you, then conact me. -Cole</p>
									</div>
							<article class="inner">
								<div class="row">
									<div class="col-24">
										<div class="row">
										<div class="form-group col-6">
										<?php
										$depts = getDepts();
										$cnt = count($depts);
										for($i = 0; $i < $cnt; ++$i) {
											echo "<h4 style=\"font-style: italic\">".$depts[$i]['dname']."</h4>";
											$members = copsByDept($depts[$i]['id']);
											if(!$members) { echo "No members to display.<br/>"; continue; }
											$mcnt = count($members);
											for($j = 0; $j < $mcnt; ++$j) {
												$ll = time()-strtotime($members[$j]['LastLogin']);
												echo getRankName($members[$j]['id'])." <b>".$members[$j]['display'];
												if(!empty($members[$j]['badge'])) echo " [".$members[$j]['badge']."]";
												$llclr = "green";
												if($ll > DAY*14) $llclr = "red";
												else if($ll > DAY*5) $llclr = "yellow";
												echo "</b> (Last Login: <span style=\"color:$llclr\">".$members[$j]['LastLogin']."</span>)";
												echo "<br/>";
											}
											$members = null;
											echo "<br/>";
										}
										?>
										</div>
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