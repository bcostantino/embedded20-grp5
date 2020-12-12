<?php
function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    } else if (isset($_SERVER['REMOTE_ADDR'])) {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    } else {
        $ipaddress = 'UNKNOWN';
    }

    return $ipaddress;
}

// completes SQL database query to insert log of a command
// db columns:
// timestamp, id, client_ip, PIN, func
function doInsQuery($connection, $sel_func, $PIN=Null, $timestamp=Null, $client_ip=Null) {
	if($PIN==Null){
		echo "Action not authorized.";
		return;
	}
	if($timestamp==Null){
		$timestamp = date("Y-m-d", time());
	}
	if($client_ip==Null){
		$client_ip = get_client_ip();
	}

	$query = "INSERT INTO `lock-table-esp`(`client_ip`, `PIN`, `func`) 
				VALUES ('$client_ip', '$PIN', '$sel_func')";
	
	if ($connection->query($query) === TRUE) {
		echo "<br>New func" . $sel_func . " logged successfully under admin.";
	} else {
		echo "Error: " . $query . "<br>" . $connection->error;
	}
}



// Define database properties
$server = "fdb29.awardspace.net";
$user = "3672926_iotlockdb";
$password = "eh]xa-HM7uqEinB5";
$dbname = "3672926_iotlockdb";

// Create db connection
$conn = new mysqli($server, $user, $password, $dbname);

// Check for connection error_get_last
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
} else {
	echo "Connection to database successful.";
}

// Insert to database
// sample admin password: 330928
if(isset($_POST['func0'])) {
	doInsQuery($conn, 0, $PIN=330928, $client_ip=get_client_ip());
} elseif(isset($_POST['func1'])) {
	doInsQuery($conn, 1, $PIN=330928, $client_ip=get_client_ip());
}
$conn->close();
?>