<?php

	$servername = "localhost";
	$username = "root";
	$password = "";

	try {
		$connect = new PDO("mysql:host=$servername;dbname=cmsdb", $username, $password);
		$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		echo "Database Bağlantısı Başarılı";
	}

	catch (PDOExpection $e) {
		echo "Database Bağlantısı Başarısız: " .$e->getMessage();
	}

?>