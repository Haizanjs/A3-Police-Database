<!doctype html>
<?php
require_once("php_includes/base.inc.php");
if(!hasPerm("pdcmd")) { redirect("index.php"); die(); }
$aperf = false;
foreach($_POST as $k => $v) {
	if(is_numeric($k)) {
		$aperf = true;
		switch(intval($v)) {
			case 1:
			processRequest($k);
			break;
			case 2:
			deleteRequest($k);
			break;
			default: break;
		}
	}
}

$cminfo = getInfo("cminfo");
$cminfo = json_decode($cminfo['data'], true);
?>
<html lang="en-US">
	<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

		<!-- Meta -->
		
		<title><?php echo $cminfo['pda']; ?> - Criminal Database</title>
		<meta name="description" content="<?php echo $cminfo['pdn']; ?> - Criminal Database">
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
        <?php if($aperf) showAlert("Requested actions have been performed.", A_SUCCESS); ?>
			<div id="content">
				<div class="container-fluid">
					<div id="heading" class="row">
						<div class="col-12">

							<header>
								<h1>User Requests</h1>
								<h2>Be mindful when accepting, as these are not restricted in use!</h2>
							</header>

						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<article class="inner">
								<div class="row">
									<div class="col-24">
										<h4>Select an action for requests you wish to process/deny, and hit Submit.</h4>
                                       	<form id="post-comment" class="inner" action="verify.php" method="post">
										<div class="row">
										<div class="form-group col-4">
										<?php
										$reqs = getRequests();
										$cnt = count($reqs);
										if(!$cnt) echo "There are no active user requests at this time.";
										else {
											echo "<div class=\"styled-select\">";
											$depts = getDepts(10000, true);
											for($i = 0; $i < $cnt; ++$i) {
												$usr = getUser($reqs[$i]['uid'], U_ID);
												echo "<b>Requester:</b> $usr[display] ($usr[uname])<br/>";
												$rdata = json_decode($reqs[$i]['data'], true);
												switch($rdata['type']) {
													case "dept":
													$dname = $depts[$usr['dept']-1]['dname'];
													$rank = json_decode($depts[$usr['dept']-1]['ranks'], true);
													echo "<b>Department:</b> $dname<br/><b>Rank:</b> ".$rank[$usr['rank']]."<br/><b>Requested Department:</b> ".$depts[$rdata['value']-1]['dname']."<br/><b>Action:</b> <select name=\"".$reqs[$i]['id']."\" id=\"".$reqs[$i]['id']."\"><option value=\"0\">No action</option><option value=\"1\">Approve</option><option value=\"2\">Deny</option></select><br/><br/>";
													break;
													case "name":
													echo "<b>Requested New Name:</b> ".$rdata['value']."<br/><b>Action:</b> <select name=\"".$reqs[$i]['id']."\" id=\"".$reqs[$i]['id']."\"><option value=\"0\">No action</option><option value=\"1\">Approve</option><option value=\"2\">Deny</option></select><br/><br/>";
													break;
													default: 
													echo "Unknown request type.<br/><br/>";
													break;
												}
											}
											echo "</div>";
										}
										?>
										</div>
									</div>
											<button type="submit" class="btn btn-color"><i class="glyphicon glyphicon-send"></i>Submit</button>
										</form>
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