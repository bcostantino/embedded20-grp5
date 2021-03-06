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
	.ctrl-panel-controls-container {
		margin: auto;
		position: relative;
		padding: 15px;
		top: 50px;
		background-color: black;
		border: 2px solid white;
		max-width: 750px;
	}
	.ctrl-panel-controls-header {
		color: white;
		text-align: center;
		font-family: 'Roboto Condensed', sans-serif;
		font-size: 2em;
	}
	.ctrl-usage-container {
		margin: auto;
		position: center;
		max-width: 500px;
		border: 2px solid var(--site-green);
		padding: 15px;
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
		<div class="ctrl-usage-container">
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
		<form action="./server-scripts/db-update.php" method="post" id="ctrl-form">
				<div class="button-spread">
				<input type="submit" name="func0" id="func0" class="unlk" value="Unlock">
				</div>
				
				<div class="button-spread">
				<input type="submit" name="func1" id="func1" class="lk" value="Lock">
				</div>
		</form>
	</div>
	</body>
</html>