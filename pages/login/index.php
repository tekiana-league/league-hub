<?php
	// Include necessary functions
	require_once('../../scripts/login-verification.php');
	
	// Test to see if the user is logged in
	if (verify_login())
	{
		// If so, redirect user to homepage
		header('location: ../../');
	}
	
	// Check to ensure fields are non-empty
	$values = false;
	if (!empty(trim($_POST['trainerID'])) && !empty(trim($_POST['password'])))
	{
		$values = true;
	}
	
	$errorText = '';
	// If both fields are filled, verify against the DB
	if ($values)
	{
		// Include DB functions
		require_once('../../scripts/db-operations.php');
		
		// Open DB connection
		$link = db_connect();
		
		// Execute statement if connected
		if (db_verify_conn($link))
		{
			// Prepare the statement
			/*$sql = "SELECT trainerID, passwordHash, fname, lname, role FROM trainers WHERE trainerID = $1";
			
			// Attempt to execute the statement
			$result = db_exec($link, $sql, array(trim($_POST['trainerID'])));
			
			// Disconnect from the DB
			db_disconnect($link);
			
			// If the username exists, verify password
			if (count($result) == 1)
			{
				$pass_auth = (password_verify(trim($_POST['password']), $result[0][1]));
			}
			else
			{
				$errorText .= 'That Trainer ID does not exist. Please try a different Trainer ID.<br/>';
			}
			
			// If the password is valid, authenticate the user
			if ($pass_auth)
			{
				// Start a new session
				session_start();
				
				// Set session variables
				$_SESSION['loggedin'] = true;
				$_SESSION['trainerID'] = $result[0][0];
				$_SESSION['fname'] = $result[0][2];
				$_SESSION['lname'] = $result[0][3];
				$_SESSION['role'] = $result[0][4];
				
				// Redirect the user to the homepage
				header('location: ../../');
			}
			else
			{
				$errorText .= 'Invalid password. Please try a different password.<br/>';
			}*/
		}
		else
		{
			$errorText .= 'Unable to connect to database. Please try again later.<br/>';
		}
	}
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
 
 <meta http-equiv="Content-Type" content="text/html;   charset=utf-8"/>
 <meta name="keywords" content=""/>
 <meta name="description" content="<Description>"/>
 <meta name="author" content="<Author Name Here>"/>
 <meta name="viewport" content="width=device-width, initial-scale=1">

 <title>Trainer Login</title>
 
 <link rel="shortcut icon" href="../../images/site-icons/gym-logo.png"/>
 
 <link type="text/css" rel="stylesheet" href="../../stylesheets/style.css"/>
 <link type="text/css" rel="stylesheet" href="../../stylesheets/login.css"/>
 
 <link href="https://fonts.googleapis.com/css?family=Cabin|Roboto&display=swap" rel="stylesheet">
 <link href="https://fonts.googleapis.com/css?family=Dancing+Script|Great+Vibes&display=swap" rel="stylesheet">
	
 <script src="https://kit.fontawesome.com/b657a3b372.js" crossorigin="anonymous"></script>

 </head>
 <body>
	<div id="login-container">
		<img id="league-logo" src="../../images/logos/league-logo.png" alt="League Logo"/>
		<h1>Trainer Login</h1>
		<form id="login-form" action="./" method="post">
			<h2 class="input-label">Trainer ID</h2>
			<input type="text" name="trainerID" autocomplete="off"/>
			<h2 class="input-label">Password</h2>
			<input type="password" name="password"/>
			<br/>
			<input type="submit" value="Login"/>
		</form>
		<p id="errorText"><?php echo $errorText;?></p>
	</div>
 </body>
 </html>