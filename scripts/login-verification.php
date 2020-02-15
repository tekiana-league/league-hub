<?php
	function verify_login()
	{
		// Initialize the session
		session_start();
		
		if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
?>