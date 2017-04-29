<!DOCTYPE HTML>
<?php
require_once("php_includes/base.inc.php");
$cminfo = getInfo("cminfo");
$cminfo = json_decode($cminfo['data'], true);

?>
<html>
<head>
<title><?php echo $cminfo['pdn']; ?> Database - Register</title>
<!-- CSS -->
<link href="css/styleLogin.css" rel="stylesheet" type="text/css" media="all"/>
<!-- Custom Theme files -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--Google Fonts-->
<link href='https://fonts.googleapis.com/css?family=Roboto:500,900italic,900,400italic,100,700italic,300,700,500italic,100italic,300italic,400' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
<!--Google Fonts-->
</head>
<body>
<div class="login">
	<div class="login-top">
		<h1><?php echo $cminfo['pdn']; ?></h1>
		<?php
		/*
		* REGISTRATION RETURN VALUES
		* false = SUCCESS
		* 1 = DUPLICATE USERNAME
		* 2 = DUPLICATE DISPLAY NAME(RP NAME)
		* 3 = DUPLICATE EMAIL
		* 4 = QUERY ERROR
		*/ 
		require_once("php_includes/base.inc.php");
		if(isset($_POST['uname']) && $_POST['pass'] == $_POST['repeat']) {
			$reg = regUser($_POST['uname'], ucwords($_POST['dname']), $_POST['pass'], $_POST['email']);
			switch($reg) {
				case 0:
				login($_POST['uname'], $_POST['pass']);
				echo "Thank you for registering, you will be redirected to the main page momentarily";
				redirect("/index.php", 3);
				break;
				case 1:
				echo "The username $_POST[uname] already exists.";
				break;
				case 2:
				echo "An account is already registered with the name $_POST[dname].";
				break;
				case 3:
				echo "This email is already in use.";
				break;
				case 4:
				echo "Registration failed due to a database error";
				break;
				default:
				echo "Something unexpected happened";
				break;
			}
		} else {
			if($_POST['pass'] != $_POST['repeat']) echo "<center>Password entries do not match!</center><br/>";
		?>
		<form method="post" action="register.php">
        	<input type="text" name="uname" placeholder="Username" required>
			<input type="text" name="dname" placeholder="Officer Name" required>
            <input type="text" name="email" placeholder="E-Mail" required>
			<input type="password" name="pass" placeholder="Password" required>
			<input type="password" name="repeat" placeholder="Repeat Password" required>
			<div class="forgot">
				<input type="submit" value="Register" >
			</div>
	    </form>
		<?php
		}
		?>
	</div>
	<div class="login-bottom">
		<h3>Already have an account? &nbsp;<a href="login.php">LOGIN</a></h3>
	</div>
</div>	
<div class="copyright">
	<p class="copyright">&copy; Copyright 2017 <a href="http://coltonbrister.com" target="_blank">Colton Brister</a></p>
</div>
</body>
</html>