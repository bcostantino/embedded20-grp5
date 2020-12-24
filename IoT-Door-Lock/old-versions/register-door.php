<?php
// register-door.php

// Get client ip address
include "./db-update.php";
$client_ip = get_client_ip();

echo $client_ip;

//endpoint = http://rowanembdoorlock.tk/register-door.php?door_id=&PIN=&door_name=&user_name=&client_location=

$door_id = $_POST["door_id"];
$PIN = $_POST["PIN"];
$door_name = $_POST["door_name"];
$user_name = $_POST["user_name"];
if(isset($_POST["client_location"])) {
	$client_location = $_POST["client_location"];
	$query = "INSERT INTO `door_info`(`door_id`, `PIN`, `door_name`, `user_name`, `client_ip`, `client_location`) VALUES ($door_id, $PIN, $door_name, $user_name, $client_ip, $client_location)";
} else {
	$query = "INSERT INTO `door_info`(`door_id`, `PIN`, `door_name`, `user_name`, `client_ip`) VALUES ($door_id, $PIN, $door_name, $user_name, $client_ip)";
}



// Define database properties
$server = "fdb29.awardspace.net";
$user = "3672926_iotlockdb";
$password = "eh]xa-HM7uqEinB5";
$dbname = "3672926_iotlockdb";

// Create db connection
$conn = new mysqli($server, $user, $password, $dbname);

// Check for connection error_get_last
if($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
} else {
	echo "Connection to database successful.";
}

if($conn->query($query) == True) {
	echo "Door registered";
} else {
	echo "Error".$query."<br>".$conn->error;
}
$conn->close();

?>