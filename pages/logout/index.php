<?php
	// Initialize the session
	session_start();
	
	// De-alocate all session variables
	$_SESSION = array();
	
	// Destroy the session
	session_destroy();
	
	// Redirect the user to the homepage
	header('location: ../../');
	exit;
?>