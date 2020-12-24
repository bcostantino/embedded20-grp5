<?php
// login user to control panel
session_start();
$PIN = $_POST["auth-pin-in"];

// set pin to authorize control panel login
if($PIN == ) {
	header("Location: ./control-panel.html");
	$_SESSION["PIN"] = $PIN;
} else {
	header("Location: ./?id=401");
}
?>
