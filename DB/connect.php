<?php
//	error_reporting(0);
	$db_host = 'localhost';
	$db_user = 'root';
	$db_password = 'rats1976';
	$db_name = 'roze2025';
	
	$link = mysqli_connect($db_host, $db_user, $db_password, $db_name);
	if (!$link) {
    	die('<p style="color:red">'.mysqli_connect_errno().' - '.mysqli_connect_error().'</p>');
	}
//	mysqli_query($link, "SET NAMES utf-8");
	$link->set_charset('utf8');	

	//echo "<p>Вы подключились к MySQL!</p>"


// Определим собственный класс исключений для ошибок MySQL
	class MySQL_Exception extends Exception {
		public function __construct($message) {
			parent::__construct($message);
		}
	}

?>
