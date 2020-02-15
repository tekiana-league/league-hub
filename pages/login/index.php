<?php
	// Include necessary functions
	require_once('./scripts/login-verification.php');
	
	// Test to see if the user is logged in
	if (verify_login())
	{
		// If so, redirect user to homepage
		header('location: ../../');
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
	<div id="login-container" action="./" method="post">
		<img id="league-logo" src="../images/logos/league-logo.png" alt="League Logo"/>
		<h1>Trainer Login</h1>
		<form id="login-form">
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