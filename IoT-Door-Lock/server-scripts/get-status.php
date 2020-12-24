<?php
// get-status.php

function getStatus($conn, $door_id) {
	$query = "SELECT * FROM `door_status` WHERE 1";
	$data = $conn->query($query);
	
	$status_log = array();
	while($row = $data->fetch_assoc()) {
		if($row["door_id"]==$door_id) {
			array_push($status_log, array($row["time"], $row["status"]));
		}
	}
	
	return $status_log;
}

function dispStatus($status_log) {
	$last_log = end($status_log);
	if($last_log[1]==0) {
		return "Unlocked";
	} elseif($last_log[1]==1) {
		return "Locked";
	}
}
?>