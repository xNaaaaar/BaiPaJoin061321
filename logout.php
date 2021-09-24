<?php

	include("google_login/config.php");
	include("extensions/functions.php");

	if(!isset($_SESSION['joiner']) || !isset($_SESSION['organizer'])) header("Location: login.php");

	unset($_SESSION['access_token']); //unset google access token
	$google_client -> revokeToken();  //unset google access token

	$_SESSION = array(); //empty the session variables
	session_unset();	//unset all session variables
	session_destroy(); //destroy the session variables
	unset($_SESSION);	//unset everything

	checkIfThereAreUsers();
?>
