<?php
function makeDoorQuery($connection, $user_name, $door_name, $door_id, $PIN) {	

	$query = "INSERT INTO `door_info`(`door_id`, `PIN`, `door_name`, `user_name`) VALUES ('$door_id', $PIN, '$door_name', '$user_name')";
	
	if ($connection->query($query) === TRUE) {
		echo "Door: ".$door_name." created successfully under ".$user_name;
	} else {
		echo "Error: ".$query."<br>".$connection->error;
	}
}

if(isset($_POST["user_name"]) && isset($_POST["door_name"]) && isset($_POST["door_id"]) && isset($_POST["PIN"])) {
	$reg_un = $_POST["user_name"];
	$reg_nm = $_POST["door_name"];
	$reg_id = $_POST["door_id"];
	$reg_pin = $_POST["PIN"];
} else {
	header("Location: ../register-door");
}

require "./db-connect.php";
$conn = dbConnect();
makeDoorQuery($conn, $reg_un, $reg_nm, $reg_id, $reg_pin);
$conn->close();
?>