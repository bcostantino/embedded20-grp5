<?php
// login user to control panel
session_start();
$login_pin = $_POST["auth-pin-in"];

function getLoginInfo($PIN) {
	require "./db-connect.php";
	$conn = dbConnect();
	
	$query = "SELECT `door_id`, `PIN`, `door_name`, `user_name` FROM `door_info` WHERE 1";
	$selection = $conn->query($query);
	
	$found = False;
	while($row = $selection->fetch_assoc()) {
		if($row["PIN"]==$PIN) {
			$_SESSION["PIN"] = $row["PIN"];
			$_SESSION["door_id"] = $row["door_id"];
			$_SESSION["door_name"] = $row["door_name"];
			$_SESSION["user_name"] = $row["user_name"];
			$found = True;
		}
	}
	return $found;
}

if(getLoginInfo($login_pin) == True) {
	header("Location: ../control-panel.php");
} else {
	header("Location: ../index.html?id=401");
}
?>