<?php
	$hostname = '95.104.192.212' ;
	$username = 'user' ;
	$passwordname = 'pass' ;
	$basename = 'db' ;
	$conn = new mysqli($hostname, $username, $passwordname, $basename) or die       ('Невозможно открыть базу') ;
	mysqli_set_charset($conn,"utf8") ;
?>