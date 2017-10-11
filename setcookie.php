<?php
	$position = $_GET["position"];
	$value = $_GET["value"];
	
	$y2k18 = mktime(0,0,0,1,1,2018);
	setcookie('position', $position, $y2k18);
	setcookie('value', $value, $y2k18);
	
	echo 'идет перенаправление';
	
	header('Location: personal.php');
	exit;
?>