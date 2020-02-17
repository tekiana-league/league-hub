<?php
	// Initialize the session
	session_start();
	
	// Figure out if registration mode was enabled
	$registrationMode = false;
	$changePassword = false;
	if (!isset($_SESSION['registrationModeEnabled']) || $_SESSION['registrationModeEnabled'] !== true)
	{
		// Do nothing
	}
	else
	{
		$registrationMode = true;
		if (!isset($_SESSION['changePasswordMode']) || $_SESSION['changePasswordMode'] !== true)
		{
			// Do nothing
		}
		else
		{
			$changePassword = true;
		}
	}
	
	$displayRegistrationFields = false;
	$successText = '';
	$errorText = '';
	if ($registrationMode && $changePassword)
	{
		// If all fields have values, open a DB connection
		$valid = false;
		if (!empty(trim($_POST['trainerID'])) && !empty(trim($_POST['oldpassword'])) && !empty(trim($_POST['newpassword'])))
		{
			$valid = true;
			// Include DB functions
			require_once('../../scripts/db-operations.php');
			
			// Open a DB connection
			$link = db_connect();
		}
		
		// If the connection is successful, get ready to validate the user
		if ($valid && db_verify_conn($link))
		{
			// Query the username to get their password
			$trainer = db_select($link, 'SELECT studentid, passwordhash FROM trainers WHERE studentid = $1', trim($_POST['trainerID']));
			
			// If the user exists, verify the password
			$auth = false;
			if (count($trainer) == 1)
			{
				if (password_verify(trim($_POST['oldpassword']), $trainer[0]['passwordhash']))
				{
					$auth = true;
				}
				else
				{
					$errorText .= 'The trainer\'s old password is incorrect. Please try a different password.<br/>';
				}
			}
			else
			{
				$errorText .= 'That Trainer ID does not exist. Please try a different Trainer ID.<br/>';
			}
			
			// If the password was verified, update the record
			if ($auth)
			{
				// Prepare the statement
				$sql = 'UPDATE trainers set passwordhash = $1 WHERE studentid = $2';
				
				// Execute the statement
				$result = db_exec($link, $sql, password_hash(trim($_POST['newpassword'])), trim($_POST['trainerID']));
				
				// Verify success
				if ($result)
				{
					$successText .= 'Password successfully updated.<br/>';
				}
				else
				{
					$errorText .= 'Something went wrong while trying to update the Trainer\'s password. Please try again later.<br/>';
				}
			}
		}
		else
		{
			$errorText .= 'Unable to connect to the database. Please try again later.<br/>';
		}
	}
	elseif ($registrationMode)
	{
		$displayRegistrationFields = true;
		// Attempt to add registration to DB
		// If all fields have values, attempt a DB connection
		$valid = false;
		if (!empty(trim($_POST['trainerID'])) && !empty(trim($_POST['fname'])) && !empty(trim($_POST['lname'])) && !empty(trim($_POST['password'])))
		{
			$valid = true;
			// Include DB functions
			require_once('../../scripts/db-operations.php');
			
			// Open a DB connection
			$link = db_connect();
		}
		
		// If the connection is successful, query the DB for the specified Trainer ID to ensure it doesn't exist
		if ($valid && db_verify_conn($link))
		{
			$trainers = db_select($link, 'SELECT studentid FROM trainers WHERE studentid = $1', trim($_POST['trainerID']));
			
			// If there are no records, insert a new one
			if (count($trainers) == 0)
			{
				// Prepare the statement
				$sql = 'INSERT INTO trainers (studentid, fname, lname, passwordhash, badges, role, bordercolor) VALUES ($1, $2, $3, $4, $5, $6, $7)';
				
				// Execute the statement
				$result = db_exec($link, $sql, trim($_POST['trainerID']), trim($_POST['fname']), trim($_POST['lname']), password_hash(trim($_POST['password']), PASSWORD_DEFAULT), '000000000000000000', '1', '696969');
				
				// Verify success
				if ($result)
				{
					$successText .= 'Trainer successfully registered.<br/>';
				}
				else
				{
					$errorText .= 'Something went wrong while trying to add the Trainer. Please try again later.<br/>';
				}
			}
			else
			{
				$errorText .= 'That Trainer ID already exists. Please contact League staff for assistance.<br/>';
			}
			db_disconnect($link);
		}
		else
		{
			$errorText .= 'Unable to connect to the database. Please try again later.<br/>';
		}
	}
	else
	{
		// Verify the registration password
		if (password_verify(trim($_POST['registrationPassword']), '$2y$10$MSpgQOW1jicRGulq22poIOTUrlG2mMJyMOombbMijw3Xu3zbcINoK'))
		{
			// Start a new session
			session_start();
			
			// Set session variables
			$_SESSION['registrationModeEnabled'] = true;
			
			// Enable registration page content
			$displayRegistrationFields = true;
		}
		elseif (strtolower(trim($_POST['registrationPassword'])) == 'changepassword')
		{
			// Start a new session
			session_start();
			
			// Set session variables
			$_SESSION['registrationModeEnabled'] = true;
			$_SESSION['changePasswordMode'] = true;
			
			// Enable password change page content
			$displayRegistrationFields = true;
			$changePassword = true;
		}
		elseif (!empty(trim($_POST['registrationPassword'])))
		{
			$errorText .= 'Invalid unlock password. Please try again.<br/>';
		}
	}
	
	$pageTitle = '';
	$submitValue = '';
	$formContent = '';
	$tooltip = '';
	if ($displayRegistrationFields && $changePassword)
	{
		// Display the appropriate password change fields
		$pageTitle = 'Trainer Password Change';
		$submitValue = 'Submit';
		$formContent = '<h2 class="input-label">Trainer ID</h2>
			<input type="text" name="trainerID" autocomplete="off"/>
			<h2 class="input-label">Old Password</h2>
			<input type="password" name="oldpassword"/>
			<h2 class="input-label">New Password</h2>
			<input type="password" name="newpassword"/>';
	}
	elseif ($displayRegistrationFields)
	{
		// Display the appropriate registration fields
		$pageTitle = 'Trainer Registration';
		$submitValue = 'Submit';
		$formContent = '<h2 class="input-label">Trainer ID</h2>
			<input type="text" name="trainerID" autocomplete="off"/>
			<h2 class="input-label">Trainer\'s First Name</h2>
			<input type="text" name="fname" autocomplete="off"/>
			<h2 class="input-label">Trainer\'s Last Name</h2>
			<input type="text" name="lname" autocomplete="off"/>
			<h2 class="input-label">Password</h2>
			<input type="password" name="password"/>';
	}
	else
	{
		// Display the registration unlock prompt
		$pageTitle = 'Unlock Trainer Registration';
		$submitValue = 'Unlock';
		$formContent = '<h2 class="input-label">Registration Unlock Password</h2>
			<input type="password" name="registrationPassword"/>';
		$tooltip = '<p>Enter \'changepassword\' in the field above if you want to change your password.</p>';
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
			<?php echo $formContent;?>
			<br/>
			<input type="submit" value="<?php echo $submitValue;?>"/>
		</form>
		<p id="successText"><?php echo $successText;?></p>
		<p id="errorText"><?php echo $errorText;?></p>
		<?php echo $tooltip;?>
	</div>
 </body>
 </html>