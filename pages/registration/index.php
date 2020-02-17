<?php
	// Initialize the session
	session_start();
	
	// Figure out if registration mode was enabled
	$registrationMode = false;
	if (!isset($_SESSION['registrationModeEnabled']) || $_SESSION['registrationModeEnabled'] !== true)
	{
		// Do nothing
	}
	else
	{
		$registrationMode = true;
	}
	
	$displayRegistrationFields = false;
	if ($registrationMode)
	{
		$displayRegistrationFields = true;
		// Attempt to add registration to DB
	}
	else
	{
		// Verify the registration password
		if (password_verify(trim($_POST['registrationPassword'])), '$2y$10$MSpgQOW1jicRGulq22poIOTUrlG2mMJyMOombbMijw3Xu3zbcINoK')
		{
			// Start a new session
			session_start();
			
			// Set session variables
			$_SESSION['registrationModeEnabled'] = true;
			
			// Enable registration page content
			$displayRegistrationFields = true;
		}
	}
	
	$pageTitle = '';
	$submitValue = '';
	if ($displayRegistrationFields)
	{
		// Display the appropriate registration fields
		$pageTitle = 'Trainer Registration';
		$submitValue = 'Submit';
	}
	else
	{
		// Display the registration unlock prompt
		$pageTitle = 'Unlock Trainer Registration';
		$submitValue = 'Unlock';
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

 <title>League Registration</title>
 
 <link rel="shortcut icon" href="../../images/site-icons/gym-logo.png"/>
 
 <link type="text/css" rel="stylesheet" href="../../stylesheets/style.css"/>
 <link type="text/css" rel="stylesheet" href="../../stylesheets/menu.css"/>
 <link type="text/css" rel="stylesheet" href="../../stylesheets/login.css"/>
 
 <link href="https://fonts.googleapis.com/css?family=Cabin|Roboto&display=swap" rel="stylesheet">
 <link href="https://fonts.googleapis.com/css?family=Dancing+Script|Great+Vibes&display=swap" rel="stylesheet">
	
 <script src="https://kit.fontawesome.com/b657a3b372.js" crossorigin="anonymous"></script>

 </head>
 <body>
	<div id="menu-show-btn" class="menu-btn" onClick="location.href = '../logout';">
		<svg id="menu-close-icon" viewBox="0 0 100 100"><image x="20" y="20" width="60" height="60" xlink:href="../../images/menu-icons/close.svg"/><svg/>
	</div>
	<div id="login-container">
		<img id="league-logo" src="../../images/logos/league-logo.png" alt="League Logo"/>
		<h1><?php echo $pageTitle;?></h1>
		<form id="login-form" action="./" method="post">
			<h2 class="input-label">Trainer ID</h2>
			<input type="text" name="studentID" autocomplete="off"/>
			<h2 class="input-label">Trainer's First Name</h2>
			<input type="text" name="fname" autocomplete="off"/>
			<h2 class="input-label">Trainer's Last Name</h2>
			<input type="text" name="lname" autocomplete="off"/>
			<h2 class="input-label">Password</h2>
			<input type="password" name="passwd"/>
			<br/>
			<input type="submit" value="<?php echo $submitValue;?>"/>
		</form>
		<p id="errorText"><?php echo $errorText;?><br/></p>
	</div>
 </body>
 </html>