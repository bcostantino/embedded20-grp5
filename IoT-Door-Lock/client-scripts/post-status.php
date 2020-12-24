<?php
function doStatusQuery($connection, $time, $status, $door_id) {
	$query = "INSERT INTO `door_status`(`time`, `door_id`, `status`) VALUES ('$time', '$door_id', $status)";
	
	if ($connection->query($query) === TRUE) {
		echo "Status: ".$status." logged successfully at ".$time;
	} else {
		echo "Error: ".$query."<br>".$connection->error;
	}
}

$content = file_get_contents("php://input");
$content = json_decode($content);

$door_id = $content->door_id;
$status = $content->status;

echo $door_id." ".$status;

date_default_timezone_set("America/New_York");
$timestamp = date("Y-m-d")." ".date("H:i:s");

require "../server-scripts/db-connect.php";
$conn = dbConnect();
doStatusQuery($conn, $timestamp, $status, $door_id);
$conn->close();
?>