<?php
	// Initialize the session
	session_start();
	
	// Test to see if registration mode is still enabled
	if ((isset($_SESSION['registrationModeEnabled']) && $_SESSION['registrationModeEnabled'] == true) || (isset($_SESSION['changePasswordMode']) && $_SESSION['changePasswordMode'] == true))
	{
		// If registration mode is enabled, destroy the session
	
		// De-allocate all session variables
		$_SESSION = array();
		
		// Destroy the session
		session_destroy();
	}
	
	// Allocate variables for login/logout button
	$btnText = '';
	$btnElem = '';
	
	// Include necessary functions
	require_once('../../scripts/login-verification.php');
	
	// Test to see if the user is logged in
	if (verify_login())
	{
		// Create logout button
		$btnText = 'logout';
		$btnElem = '<text class="menu-btn-text" x="625" y="255">Logout</text>';
	}
	else
	{
		// Create login button
		$btnText = 'login';
		$btnElem = '<text class="menu-btn-text" x="635" y="255">Login</text>';
	}
	
	$displayCustomization = false;
	$displayBadgeBtn = false;
	$postLink = '';
	// If the Trainer's role is 2 or higher, display the action button
	if (intval($_SESSION['role']) > 2)
	{
		$displayBadgeBtn = true;
	}
	
	// Handle GET requests for trainerID
	$trainerNum = '';
	$fname = '';
	$lname = '';
	$bgImg = '';
	$fgImg = '';
	$overlayImg = '';
	$cardNum = '';
	$cardColor = '696969';
	if (isset($_GET['trainerID']))
	{
		// Query the DB for the trainer
		require_once('../../scripts/db-operations.php');
		
		// Connect to the DB
		$link = db_connect();
		
		// If connection success, query for the trainer
		if (db_verify_conn($link))
		{
			// Prepare the statement
			$sql = 'SELECT studentid, fname, lname, badges, role, bordercolor, bgimg, fgimg, overlayimg, trainernum, earned_time FROM trainers WHERE studentid = $1';
			
			// Execute the statement
			$result = db_select($link, $sql, trim($_GET['trainerID']));
			
			// Disconnect from the DB
			db_disconnect($link);
			
			// If the username exists, display the information
			if (count($result) == 1)
			{
				$trainerNum = $result[0]['studentid'];
				$fname = $result[0]['fname'];
				$lname = $result[0]['lname'];
				$bgImg = $result[0]['bgimg'];
				$fgImg = $result[0]['fgimg'];
				$overlayImg = $result[0]['overlayimg'];
				$cardNum = $result[0]['trainernum'];
				$cardColor = $result[0]['bordercolor'];
				
				// Set destination for POST requests
				$postLink = '?trainerID='.$trainerNum;
				
				// If the trainer's ID matches, allow them to customize their card
				if ($trainerNum == $_SESSION['trainerID'])
				{
					$displayCustomization = true;
					// But make sure they can't do anything to themselves
					$displayBadgeBtn = false;
				}
			}
			else
			{
				$trainerNum = '404';
				$fname = 'Trainer';
				$lname = 'Not Found';
			}
		}
	}
	
	// Display the proper content
	$cardCustomization = '';
	if ($displayCustomization)
	{
		$cardCustomization = '<form id="update-form" action="./'.$postLink.'" method="post">
			<input type="text" name="bgImg" placeholder="Trainer Card Background URL"/>
			<input type="text" name="fgImg" placeholder="Trainer Card Foreground URL"/>
			<input type="text" name="overlayImg" placeholder="Trainer Card Overlay URL"/>
			<input type="text" name="cardNumber" placeholder="Trainer Card Number (3 digits max.)"/>
			<br/>
			<h2 class="input-label">Card Color:</h2>
			<input type="color" name="cardColor"/>
			<br/>
			<input type="submit" value="Update Card"/>
			<p id="successText"><?php echo $successText;?></p>
			<p id="errorText"><?php echo $errorText;?></p>
		</form>';
	}
	$badgeButton = '';
	if ($displayBadgeBtn)
	{
		$btnText = '';
		if (intval($_SESSION['role']) == 2)
		{
			$btnText = 'Steal Badge';
			if ($trainerHasBadgeStolen)
			{
				$btnText = 'Return Badge';
			}
		}
		else
		{
			$btnText = 'Award Badge';
		}
		$badgeButton = '<form id="badge-form" action="./'.$postLink.'" method="post">
			<input type="hidden" name="role" value="123"/>
			<input type="submit" value="'.$btnText.'"/>
		</form>';
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

 <title>Trainer #<?php echo $trainerNum;?></title>
 
 <link rel="shortcut icon" href="../../images/site-icons/gym-logo.png"/>
 
 <link type="text/css" rel="stylesheet" href="../../stylesheets/style.css"/>
 <link type="text/css" rel="stylesheet" href="../../stylesheets/menu.css"/>
 <link type="text/css" rel="stylesheet" href="../../stylesheets/home.css"/>
 <link type="text/css" rel="stylesheet" href="../../stylesheets/login.css"/>
 <link type="text/css" rel="stylesheet" href="../../stylesheets/trainer-card.css"/>
 
 <link href="https://fonts.googleapis.com/css?family=Cabin|Roboto&display=swap" rel="stylesheet">
 <link href="https://fonts.googleapis.com/css?family=Dancing+Script|Great+Vibes&display=swap" rel="stylesheet">
	
 <script src="https://kit.fontawesome.com/b657a3b372.js" crossorigin="anonymous"></script>
 
 <script src="../../scripts/menu.js"></script>
 <script src="../../scripts/trainer-card.js"></script>

 </head>
 <body>
	<div id="menu-show-btn" class="menu-btn" onClick="displayMenu();">
		<svg id="menu-close-icon" viewBox="0 0 100 100"><image x="20" y="20" width="60" height="60" xlink:href="../../images/menu-icons/menu.svg"/><svg/>
	</div>
	<div id="menu-overlay">
		<div id="menu-exit-btn" class="menu-btn" onClick="displayMenu();">
			<svg id="menu-close-icon" viewBox="0 0 100 100"><image x="20" y="20" width="60" height="60" xlink:href="../../images/menu-icons/close.svg"/><svg/>
		</div>
		<div id="menu-border"><div id="menu-bg"></div></div>
		<svg id="menu-buttons" viewBox="0 0 1000 1000">
			<line class="menu-line" x1="500" y1="500" x2="250" y2="325"/>
			<path class="menu-line" d="M500 500Q350 650 225 650"/>
			<path class="menu-line" d="M500 500Q700 550 800 400"/>
			<path class="menu-line" d="M500 500Q500 250 675 150"/>
			<line class="menu-line" x1="500" y1="500" x2="750" y2="725"/>
			<circle id="map-btn" class="menu-btn" cx="225" cy="650" r="75"/>
			<image class="menu-btn-img" x="-2" y="628" width="100" height="100" xlink:href="../../images/menu-icons/map.svg"/>
			<circle id="login-btn" class="menu-btn" cx="675" cy="150" r="75" onClick="location.href='../<?php echo $btnText;?>';"/>
			<image class="menu-btn-img" x="572" y="270" width="100" height="100" xlink:href="../../images/menu-icons/<?php echo $btnText;?>.svg"/>
			<circle id="register-btn" class="menu-btn" cx="750" cy="725" r="75" onClick="location.href='../registration';"/>
			<image class="menu-btn-img" x="497" y="845" width="100" height="100" xlink:href="../../images/menu-icons/registration.svg"/>
			<circle id="gyms-btn" class="menu-btn" cx="250" cy="325" r="95" onClick="location.href='../gym-leaders';"/>
			<image class="menu-btn-img" x="95" y="330" width="130" height="130" xlink:href="../../images/menu-icons/gym.svg"/>
			<circle id="rules-btn" class="menu-btn" cx="800" cy="400" r="95" onClick="location.href='../rulebook';"/>
			<image class="menu-btn-img" x="605" y="527" width="130" height="130" xlink:href="../../images/menu-icons/book.svg"/>
			<circle id="profile-btn" class="menu-btn" cx="500" cy="500" r="135" onClick="location.href='../trainer-card';"/>
			<image class="menu-btn-img" x="265" y="525" width="180" height="180" xlink:href="../../images/menu-icons/id-card.svg"/>
			<text class="menu-btn-text" x="410" y="670">Trainer Card</text>
			<text class="menu-btn-text" x="155" y="455">Gym Leaders</text>
			<text class="menu-btn-text" x="140" y="760">Region Map</text>
			<text class="menu-btn-text" x="735" y="530">Rulebook</text>
			<?php echo $btnElem;?>
			<text class="menu-btn-text" x="630" y="835">Registration Mode</text>
		</svg>
	</div>
	<img id="league-logo" src="../../images/logos/league-logo.png" alt="League Logo"/>
	<h1>Trainer #<?php echo $trainerNum;?>: <?php echo $fname;?> <?php echo $lname;?></h1>
	<?php echo $badgeButton;?>
	<br/>
	<form id="search-form" action="./" method="get">
		<input type="text" name="trainerID" placeholder="Search for trainer ID"/>
		<input type="submit" value="Search"/>
	</form>
	<br/>
	<div class="card-container" style="--cardColor:#<?php echo $cardColor;?>" onClick="flipCard(this);">
		<div class="trainer-card">
			<div class="card-front">
				<div class="card-front-bg" style="--bgImg:url('<?php echo $bgImg;?>')"></div>
				<div class="card-front-fg" style="--bgImg:url('<?php echo $fgImg;?>')"></div>
				<div class="card-front-overlay" style="--bgImg:url('<?php echo $overlayImg;?>')"></div>
				<div class="card-front-border-container"><div class="card-front-border"></div></div>
				<p class="card-front-text"><?php echo $cardNum;?></p>
			</div>
			<div class="card-back">
				<p class="card-back-text">Badges</p>
			</div>
		</div>
	</div>
	<?php echo $cardCustomization;?>
 </body>
 </html>