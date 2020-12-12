<?php
	// takes db info
	$server = "fdb29.awardspace.net";
	$user = "3672926_iotlockdb";
	$password = "eh]xa-HM7uqEinB5";
	$dbname = "3672926_iotlockdb";
	
	//endpoint = http://www.rowanembdoorlock.tk/db-query.php?door_id={door_id}&PIN={PIN}
	
	// takes most recent request, id and PASSWORD (PIN)
	// response_type = 'all' or 'most_recent'
	
	$response_type = $_GET["response_type"];
	$most_recent_req = $_GET["recent_req"];
	$door_id = $_GET["door_id"];
	$PIN = $_GET["PIN"];
	
	// Connect to database
	$conn = new mysqli($server, $user, $password, $dbname);
	
	// test connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
		
		// send db connection status code 502 -- Bad Gateway
		http_response_code(502);
	} else {
		// send db connection status code 202 -- Accepted
		http_response_code(202);	
	}
	
	// get data from db SQL query
	$sql = "SELECT * FROM `lock-table-esp` WHERE 1";
	$selection = $conn->query($sql);
	
	// find related data (by PIN)
	$found = False;
	$commands = array();
	while($row = $selection->fetch_assoc()) {
		if($row["PIN"]==$PIN) {
			$commands[$row["time"]] = array($row["time"], $row["func"], $row["id"]);
			$found = True;
		}
	}
	
	// define response
	$response = array();
	
	// fill response
	if($found = True) {
		http_response_code(200);
		
		// check response type and filter
		if($response_type=="all") {
			$response["recent_req"] = end($commands)[2];
			$response["command_log"] = $commands;
			echo json_encode($response);
			
		} elseif($response_type=="most_recent") {
			$response["recent_req"] = end($commands)[2];
			
			// check if new commands
			if(end($commands)[2]==$most_recent_req) {
				$response["command_log"] = 'no outstanding commands';
				echo json_encode($response);
			} else {
				$response["command_log"] = end($commands);
				echo json_encode($response);
			}
		}
	} else {
		$response["recent_req"] = $most_recent_req;
		$response["command_log"] = 'no outstanding commands';
		echo json_encode($response);
	}
	$conn->close();
?>