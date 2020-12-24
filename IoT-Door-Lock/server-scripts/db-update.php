<?php
session_start();
// completes SQL database query to insert log of a command
// db columns:
// timestamp, id, PIN, func
function doInsQuery($connection, $sel_func, $PIN=Null, $door_id='N') {
	if($PIN==Null){
		echo "Action not authorized.";
		return;
	}
	
	date_default_timezone_set("America/New_York");
	$timestamp = date("Y-m-d")." ".date("H:i:s");
	
	$query = "INSERT INTO `esp_command_log`(`time`, `door_id`, `PIN`, `func`) VALUES ('$timestamp', '$door_id', $PIN, $sel_func)";
	
	if ($connection->query($query) === TRUE) {
		echo "New ".$sel_func." command logged successfully under user: ".$_SESSION["user_name"]."<br>";
	} else {
		echo "Error in: ".$query."<br>".$connection->error;
	}
}

// Insert to database
require "./db-connect.php";
$conn = dbConnect();

if(isset($_POST['func0'])) {
	doInsQuery($conn, 0, $PIN=$_SESSION["PIN"], $door_id=$_SESSION["door_id"]);
} elseif(isset($_POST['func1'])) {
	doInsQuery($conn, 1, $PIN=$_SESSION["PIN"], $door_id=$_SESSION["door_id"]);
}
$conn->close();

echo "Redirecting back to control panel...";
sleep(3);
$URL="../control-panel.php";
echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';

?>