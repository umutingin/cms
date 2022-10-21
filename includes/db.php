<?php
	$servername = "localhost";
	$username = "umutingi_cms_u";
	$password = "7@r-XMSL2I]e";
	$db = "umutingi_cms";

	$connect = mysqli_connect($servername, $username, $password, $db);

	if (!$connect) {
		die("Database Bağlantısı Kurulamadı:" . mysqli_connect_error());
	}
?>
