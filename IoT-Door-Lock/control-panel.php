<?php
	session_start();
	if(isset($_SESSION["PIN"]) && isset($_SESSION["door_id"]) != True) {
		header("Location: ./");
	}
?>

<!DOCTYPE html>
<html>
	<head>
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&display=swap" rel="stylesheet">
	<link rel = "stylesheet"
    type = "text/css"
	href = "./styles/myStyle.css" />
	<style>
	body {
		overflow: hidden;
	}
	.site-content {
		position: relative;
		top: 30px;
		max-width: 100%;
		border: 2px solid white;
	}
	.ctrl-panel-nav-bar {
		background-color: var(--site-purp);
		max-width: 95%;
		height: 3.8em;
		position: relative;
		left: 2.5%;
		top: 15px;
	}
	.ctrl-panel-page-title {
		margin: auto;
		clear: both;
		color: white;
		text-decoration: none;
		text-transform: uppercase;
		text-shadow: 0px 3px 3px black;
		font-family: 'Roboto Condensed', sans-serif;
		font-size: 1.5em;
	}
	.left-head {
		position: relative;
		left: 1%;
		top: 0em;
	}
	.home-right-head {
		position: relative;
		left: 92%;
		top: -1.15em;
		color: white;
		font-size: 2.2em;
		text-decoration: none;
	}
	.door-info-container {
		position: relative;
		left: 22%;
		top: 50px;
		max-width: 25%;
		background-color: black;
		border: 2px solid white;
		padding: 1%;
	}
	.door-info {
		margin: 0 auto;
		display: inline;
		font-family: 'Roboto Condensed', sans-serif;
		font-size: 1.2em;
		color: white;
	}
	.status-green {
		font-family: 'Roboto Condensed', sans-serif;
		font-size: 1.2em;
		display: inline;
		color: var(--site-green);
	}
	.status-red {
		font-family: 'Roboto Condensed', sans-serif;
		font-size: 1.2em;
		display: inline;
		color: var(--site-red);
	}
	.ctrl-panel-controls-container {
		position: relative;
		left: 51%;
		top: -480px;
		max-width: 25%;
		background-color: black;
		border: 2px solid white;
		padding: 1%;
	}
	.ctrl-panel-controls-header {
		color: white;
		text-align: center;
		font-family: 'Roboto Condensed', sans-serif;
		font-size: 2em;
	}
	.ctrl-usage-container {
		position: relative;
		left: 22%;
		top: 75px;
		max-width: 25%;
		border: 2px solid var(--site-green);
		padding: 1%;
		height: 280px;
	}
	.ctrl-usage-head {
		text-align: center;
		font-family: 'Roboto Condensed', sans-serif;
		color: white;
		font-size: 2em;
	}
	.usage-client-head {
		color: white;
		text-align: center;
		font-family: 'Roboto Condensed', sans-serif;
		font-size: 1.5em;
	}
	.usage-server-head {
		color: white;
		text-align: center;
		font-family: 'Roboto Condensed', sans-serif;
		font-size: 1.5em;
	}
	.usage-info {
		color: white;
		text-align: justify;
		font-family: 'Roboto Condensed', sans-serif;
		font-size: 1em;
	}
	.command-log-container {
		position: relative;
		left: 51%;
		top: -455px;
		max-width: 25%;
		border: 2px solid white;
		padding: 1%;
		height: 280px;
	}
	.table-wrapper {
		position: relative;
	}
	.table-scroll {
		overflow: auto;
		height: 210px;
		margin-top: 20px;
	}
	#commands {
		font-family: 'Roboto Condensed', sans-serif;
		border-collapse: collapse;
		width: 100%;
	}
	#commands tr {
		background-color: #ddd;
	}
	#commands td, #commands th {
		border: 1px solid #ddd;
		padding: 5px;
	}
	#commands tr:nth-child(even){background-color: #f2f2f2;}
	#commands tr:hover {background-color: #ddd;}
	#commands th {
		text-align: left;
		background-color: var(--site-blue);
		color: white;
	}
	</style>
	</head>
	<body>
		<div class="ctrl-panel-nav-bar">
			<div class="ctrl-panel-page-title">
				<h1 class="left-head">
				Controls
				</h1>
				<a class="home-right-head" href="./">
				Home
				</a>
				<br>
			</div>
		</div>
		<div class="door-info-container">
			<?php
				require "./server-scripts/get-status.php";
				require "./server-scripts/db-connect.php";
				
				$conn = dbConnect();
				$_SESSION["status_log"] = getStatus($conn, $_SESSION["door_id"]);
				if(end($_SESSION["status_log"])[1]==1) {
					$_SESSION["status_css_class"] = 'status-green';
					$_SESSION["status"] = "locked";
				} elseif(end($_SESSION["status_log"])[1]==0) {
					$_SESSION["status_css_class"] = 'status-red';
					$_SESSION["status"] = "unlocked";
				}
				
				echo "<h1 class='ctrl-usage-head'>
				Door Info
				</h1>
				<p class='door-info'>
				<b>Door Name:</b> ".$_SESSION["door_name"]."<br>
				<b>User Name:</b> ".$_SESSION["user_name"]."<br>
				<b>Door ID:</b> ".$_SESSION["door_id"]."<br>
				<b>PIN:</b> ".$_SESSION["PIN"]."<br>
				<b>Status:</b><div class=".$_SESSION["status_css_class"]."> <b>".$_SESSION["status"]."</b></div>
				</p>";
			?>
		</div>
		
		<div class="ctrl-usage-container">
			<h1 class="ctrl-usage-head">
			Usage
			</h1>
			<p class="usage-info">
			The client side consists of the ESP8266 MCU which directly accesses the door lock. The client is constantly making HTTP GET requests to the server which returns data based on some security parameters. To change the state of the lock, view the current status under "Door Info", and click the button that would change that state.<br><br>When the ESP receives a new state, the servo will turn to the new position, and the corresponding color LED will blink 3 times.  The ESP then sends a confirmation to the server that the status of the lock has changed, and the website is then updated to reflect the new status (0 - unlocked, 1 - locked).
			</p>
		</div>
		
		<div class="ctrl-panel-controls-container">
			<h1 class='ctrl-panel-controls-header'>
			Control Panel
			</h1>
			<br>
			<form action="./server-scripts/db-update.php" method="post" id="ctrl-form">
					<div class="button-spread">
					<input type="submit" name="func0" id="func0" class="unlk" value="Unlock">
					</div>
					
					<div class="button-spread">
					<input type="submit" name="func1" id="func1" class="lk" value="Lock">
					</div>
			</form>
		</div>
		
		<div class="command-log-container">
			<h1 class='ctrl-panel-controls-header'>
			Command Log
			</h1>
			<div class="table-wrapper">
				<div class="table-scroll">
					<table id="commands">
						<tr>
							<th>Time</th>
							<th>Command</th>
						</tr>
						<?php
							$reverted = new ArrayIterator(array_reverse($_SESSION["status_log"]));
						
							foreach($reverted as &$val) {
								echo "<tr>
									<td>".$val[0]."</td>
									<td>".$val[1]."</td>
									</tr>";
							}
						?>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>