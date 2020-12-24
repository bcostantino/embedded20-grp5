<?php
//db-connect.php

function dbConnect() {
	// Create db connection
	require "db-info.php";
	$connection = new mysqli($server, $user, $password, $dbname);

	// Check for connection error_get_last
	if ($connection->connect_error) {
		return $connection->connect_error;
	} else {
		return $connection;
	}
}
?>