<?php
if(isset($_COOKIE["LSTZ"])){
	$usrTZ = htmlspecialchars($_COOKIE["LSTZ"]);
}else{
	$usrTZ = "UTC";
}
$tinfo = getInfo("threat");
$tinfo = json_decode($tinfo['data'], true);
$t = intval($tinfo['level']);
$col = "#00aa00";
switch($t) {
	case 1:
	$col = "<span style=\"color: #00aa00; font-weight: bold\">GREEN</span>";
	break;
	case 2:
	$col = "<span style=\"color: #ffaa00; font-weight: bold\">AMBER</span>";
	break;
	case 3:
	$col = "<span style=\"color: red; font-weight: bold\">RED</span>";
	break;
	case 4:
	$col = "<span style=\"color: maroon; font-weight: bold\">DEEP RED</span>";
	break;
	case 5:
	$col = "<span style=\"color: black; font-weight: bold\" id=\"mlaw\">MARTIAL LAW</span><script type=\"text/javascript\">var m = document.getElementById('mlaw'); setInterval('if(m.style.opacity == 0.0) m.style.opacity = 1.0; else m.style.opacity = 0.0;', 250);</script>";
	break;
	default:
	$col = "<span style=\"color: white; font-weight: bold\">UNKNOWN</span>";
}
$tickerLimit = "1";
$onlyActive = true;
$boloTicker = getBolos($tickerLimit,$onlyActive);
if($boloTicker){
?>
<div id="boloContainer">
	<div id="boloTicker">
		<ul id="boloFeed">
		<?php
		$btformat = "H:i:s";
		$btcnt = count($boloTicker);
		
		echo "<li><b>$btcnt Active Bolos Past Hour:</b></li>";
		for($i = 0; $i < $btcnt; $i++) {
			$item = "item".$i;
			$btutcTS = antiXSS($boloTicker[$i]['RealDate']);
			$usrTS = date_create($btutcTS, new DateTimeZone("UTC")) -> setTimeZone(new DateTimeZone($usrTZ)) -> format($btformat);
			$divider = ($i == $btcnt-1) ? "<b></b>" : "<b>|</b>";
			echo "<li id='".$item."'><i>".$usrTS."</i>".titleFormat(antiXSS($boloTicker[$i]['info'])).$divider."</li>\n";
		}
		?>
		</ul>
	</div>
</div>
<?php } ?>

