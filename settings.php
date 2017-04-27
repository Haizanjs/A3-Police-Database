<?php
require_once("php_includes/base.inc.php");
if(!isLoggedIn()){
	echo "You are not logged in... redirecting.";
	redirect("login.php");
	die();
}
$astr = "";
$usr = getUser($_SESSION['uname'], U_UNAME);
$reqs = getRequests($usr['id']);
$cminfo = getInfo("cminfo");
$cminfo = json_decode($cminfo['data'], true);
if(isset($_POST['usr'])) {
	if($_POST['usr'] != $usr['uname']) {
		if(!getUser($_POST['usr'], U_UNAME)) {
			setUData($_POST['usr'], U_UNAME);
			$usr['uname'] = $_POST['usr'];
			$astr .= "Your username is now $usr[uname]. ";
		}
	}
	if($_POST['rlName'] != $usr['display']) {
		createRequest("name", $_POST['rlName']);
		$astr .= "You have requested a display name change to $_POST[rlName]. ";
	}
	if(!empty($_POST['opWrd']) && !empty($_POST['opWrd']) && !empty($_POST['opWrd']))
		if(hashPass($_POST['opWrd'], $usr['salt']) == $usr['phash'] && $_POST['cpWrd'] == $_POST['npWrd']) {
			setUData($_POST['cpWrd'], U_PASS);
			$astr .= "Your password has been changed. ";
		}
}
if(isset($_POST['untNum'])) {
	if(!empty($_POST['untNum']) && $usr['badge'] != $_POST['untNum']) {
		setUData($_POST['untNum'], U_BADGE);
		$usr['badge'] = $_POST['untNum'];
		$astr .= "Your badge number is now $usr[badge]. ";
	}
	if($_POST['telNum'] != 0 && $_POST['telNum'] != $usr['phone']) {
		setUData($_POST['telNum'], U_PHONE);
		$usr['phone'] = $_POST['telNum'];
		$astr .= "Your stored phone number is now $usr[phone]. ";
	}
	if(intval($_POST['active']) != intval($usr['dept']) && intval($_POST['active']) > 0) {
		createRequest("dept", $_POST['active']);
		$astr .= "Your transfer request has been put in. ";
	}
}
?>
<!doctype html>
<html lang="en-US">
	<head>

		<!-- Meta -->
		<meta charset="UTF-8">
		<title><?php echo $cminfo['pda']; ?> - Officer Settings</title>
		<meta name="description" content="<?php echo $cminfo['pdn']; ?> - Officer Settings">
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
        <?php if(!empty($astr)) showAlert($astr, A_SUCCESS); ?>
			<div id="content">
				<div class="container-fluid">
					<div id="heading" class="row">
						<div class="col-12">

							<header>
								<h1>User Settings</h1>
                                <h2>Selections marked with a * require command approval before changes can take place.</h2>
                                <h2>YOU HAVE <?php echo count($reqs); ?> REQUESTS PENDING REVIEW</h2>
                                <?php if($usr['dept'] == PENDING) showAlert("You are not yet placed in a department, please request a transfer to the department you are being hired into.", A_WARN); ?>
							</header>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<article class="inner">
								<div class="row">
                                	<form action="settings.php" method="post">
									<div class="form-group col-5" style="border:double">
                                	<div class="form-group col-12">
                                    	<h3>Login Information</h3>
                                    	<label for="usrName">Username:</label>
											<input type="text" name="usr" value="<?php echo $usr['uname']; ?>" class="form-control" id="usrName" style="width:75%">
										<label for="rlName">Real Name:<strong>*</strong></label>
											<input type="text" name="rlName" value="<?php echo $usr['display']; ?>" class="form-control" id="rlName" style="width:75%">
                                            <br/>
                                            <p style="font-weight: bold">Change Password:</p>
                                        <label for="opWrd">Current Password:</label>
											<input type="password" style="width:75%; appearance:password" id="opWrd" class="form-control" value="" name="opWrd">
                                            <label for="npWrd">New Password:</label>
											<input type="password" style="width:75%; appearance:password" id="npWrd" class="form-control" value="" name="npWrd">
                                            <label for="cpWrd">Confirm Password:</label>
											<input type="password" style="width:75%; appearance:password" id="cpWrd" class="form-control" value="" name="cpWrd">
                                            <br>
                                     <button type="submit" class="btn btn-color"><i class="glyphicon glyphicon-send"></i>Submit Changes</button>
                                     </div>
                                    </form>
                                    </div>
                                    <form action="settings.php" method="post">
									<div class="form-group col-3" style="border:double">
                                	<div class="form-group col-12">
                                    	<h3>General Information</h3>
                                    	<label for="unitNum">Badge Number:</label>
											<input type="text" name="untNum" value="<?php echo $usr['badge']; ?>" class="form-control" id="unitNum" style="width:35%">
                                        <div class="styled-select">
                                        <label>Department Transfer:<strong>*</strong></label>
                                        <select style="width: auto" name="active" class="form-control">
                                        	<option value="0">Not transferring (Click to Transfer)</option>
												<?php
                                                $depts = getDepts();
												$dcnt = count($depts);
												for($i = 0; $i < $dcnt; ++$i) {
													echo "<option value=\"".$depts[$i]['id']."\">".$depts[$i]['dname']."</option>";
												}
												?>
										</select>
                                        </div>
                                            <br>
                                     <button type="submit" class="btn btn-color"><i class="glyphicon glyphicon-send"></i>Submit Changes</button>
                                     </div>
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