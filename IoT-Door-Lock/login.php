<?php
// login user to control panel
session_start();
$PIN = $_POST["auth-pin-in"];

if($PIN == 330928) {
	header("Location: ./control-panel.html");
	$_SESSION["PIN"] = $PIN;
} else {
	header("Location: ./?id=401");
}
?>