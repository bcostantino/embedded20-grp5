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
	href = "./styles/control-panel-style.css" />
	<link rel = "stylesheet"
    type = "text/css"
	href = "./styles/myStyle.css" />
	<style>
	.door-info-container {
		position: relative;
		left: 400px;
		top: -150px;
		max-width: 25%;
		background-color: black;
		border: 2px solid white;
		padding: 15px;
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
		left: 240px;
		top: 50px;
		max-width: 25%;
		background-color: black;
		border: 2px solid white;
		padding: 15px;
	}
	.ctrl-panel-controls-header {
		color: white;
		text-align: center;
		font-family: 'Roboto Condensed', sans-serif;
		font-size: 2em;
	}
	.ctrl-usage-container {
		position: relative;
		left: 400px;
		top: -120px;
		max-width: 25%;
		border: 2px solid var(--site-green);
		padding: 15px;
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
	</style>
	</head>
	<body>
		<div class="ctrl-panel-nav-bar">
			<div class="ctrl-panel-page-title">
				<h1 class="left-head">
				Control Panel
				</h1>
				<a class="home-right-head" href="./">
				Home
				</a>
				<br>
			</div>
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
			<h2 class="usage-client-head">
			Client Side Usage
			</h2>
			<p class="usage-info">
			The client side consists of the ESP8266 MCU which directly accesses the door lock. The client is constantly making HTTP GET requests to the server which returns data based on some security parameters. To 
			</p>
			<h2 class="usage-server-head">
			Server Side Usage
			</h2>
			<p class="usage-info">
			To interact with your door from the website, simply click one of the buttons below. The server will log your command in the database along with other info (i.e. PIN code, function, timestamp, etc). This data is avalable to the user using HTTP requests as described above.
			</p>
		</div>
	</body>
</html>