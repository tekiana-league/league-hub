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
	$trainerURL = '';
	
	// Test to see if the user is logged in
	if (verify_login())
	{
		// Create logout button
		$btnText = 'logout';
		$btnElem = '<text class="menu-btn-text" x="625" y="255">Logout</text>';
		$trainerURL .= '/?trainerID=' . $_SESSION['trainerID'];
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
	if (intval($_SESSION['role']) >= 2)
	{
		$displayBadgeBtn = true;
	}
	
	$gymLeaderBadgeElem = '';
	$badgeElements = '';
	$trainerHasBadgeStolen = false;
	// Handle card customization POST requests
	$successText = '';
	$errorText = '';
	if (isset($_POST['bgImg']) || isset($_POST['fgImg']) || isset($_POST['overlayImg']) || isset($_POST['cardNumber']) || isset($_POST['cardColor']))
	{
		// Include DB functions
		require_once('../../scripts/db-operations.php');
		
		// Connect to the DB
		$link = db_connect();
		
		// If the connection worked, get ready to insert
		if (db_verify_conn($link))
		{
			// Prepare the base SQL string
			$sql = 'UPDATE trainers set ? WHERE studentid = $1';
			
			// Add filled fields
			$counter = 2;
			$sqlAdditions = '';
			if (isset($_POST['bgImg']) && !empty(trim($_POST['bgImg'])))
			{
				$sqlAdditions .= 'bgimg = \''.str_replace(';', '', trim($_POST['bgImg'])).'\'';
				$counter++;
			}
			if (isset($_POST['fgImg']) && !empty(trim($_POST['fgImg'])))
			{
				if ($counter > 2){$sqlAdditions .= ', ';}
				$sqlAdditions .= 'fgimg = \''.str_replace(';', '', trim($_POST['fgImg'])).'\'';
				$counter++;
			}
			if (isset($_POST['overlayImg']) && !empty(trim($_POST['overlayImg'])))
			{
				if ($counter > 2){$sqlAdditions .= ', ';}
				$sqlAdditions .= 'overlayimg = \''.str_replace(';', '', trim($_POST['overlayImg'])).'\'';
				$counter++;
			}
			if (isset($_POST['cardNumber']) && !empty(trim($_POST['cardNumber'])) && ctype_digit(trim($_POST['cardNumber'])) && strlen(trim($_POST['cardNumber'])) <= 3)
			{
				if (strlen(trim($_POST['cardNumber'])) < 3)
				{
					// Store number on card
					$cardNumber = trim($_POST['cardNumber']);
					// Append zeros
					$_POST['cardNumber'] = '';
					for ($i=0; $i<strlen($cardNumber); $i++)
					{
						$_POST['cardNumber'] .= '0';
					}
					$_POST['cardNumber'] .= $cardNumber;
				}
				// Query the DB for the card number, and see if it is unique
				$result = db_select($link, 'SELECT trainernum FROM trainers where trainernum = $1', trim($_POST['cardNumber']));
				if (count($result) < 1)
				{
					if ($counter > 2){$sqlAdditions .= ', ';}
					$sqlAdditions .= 'trainernum = \''.str_replace(';', '', trim($_POST['cardNumber'])).'\'';
					$counter++;
				}
				else
				{
					$errorText .= 'That Trainer Card Number is already in use. Please input a different Trainer Card Number.<br/>';
				}
			}
			if (isset($_POST['cardColor']) && !empty(trim($_POST['cardColor'])) && $_POST['cardColor'] != '%23000000' && substr(str_replace(';', '', trim($_POST['cardColor'])), 1, 6) != '000000')
			{
				if ($counter > 2){$sqlAdditions .= ', ';}
				$sqlAdditions .= 'bordercolor = \''.substr(str_replace(';', '', trim($_POST['cardColor'])), 1, 6).'\'';
				$counter++;
			}
			$sql = str_replace('?', $sqlAdditions, $sql);
			
			// Execute the query
			$result = db_exec($link, $sql, $_SESSION['trainerID']);
			
			// Disconnect from the DB
			db_disconnect($link);
			
			// Verify the result
			if ($result)
			{
				$successText .= 'Trainer Card updated successfully.<br/>';
			}
			else
			{
				$errorText .= 'There was an error updating your Trainer Card. Please try again later.<br/>';
			}
		}
		else
		{
			$errorText .= 'Unable to connect to the database. Please try again later.<br/>';
		}
	}
	elseif (isset($_POST['role']) && intval($_SESSION['role']) >= 2)// Handle Badge Button POST requests
	{
		// Include DB functions
		require_once('../../scripts/db-operations.php');
		
		// Connect to the DB
		$link = db_connect();
		
		// If connection success, query for the trainer
		$badges = array();
		if (db_verify_conn($link))
		{
			// Prepare the statement
			$sql = 'SELECT badges FROM trainers WHERE studentid = $1';
			
			// Execute the statement
			$result = db_select($link, $sql, trim($_GET['trainerID']));
			
			// Disconnect from the DB
			db_disconnect($link);
			
			// If the username exists, store the badge string from the DB as an array
			if (count($result) == 1)
			{
				$badges = str_split(strval($result[0]['badges']));
			}
		}
		
		// If the badge array isn't empty, get ready to manipulate it
		if (count($badges) > 0)
		{
			// Get the index value of the last badge
			$lastBadgeIndex = 0;
			$stolenBadgeIndex = 0;
			foreach ($badges as $badge)
			{
				if (ctype_lower($badge))
				{
					$trainerHasBadgeStolen = true;
					$stolenBadgeIndex = $lastBadgeIndex;
				}
				if ($badge != '0')
				{
					$lastBadgeIndex++;
				}
			}
			
			// Include badge manipulation functions
			require_once('../../scripts/badge-conversions.php');
			
			// Check trainer's role to see what the button does
			$badgeStr = '';
			$sqlAdd = '';
			$alreadyHasBadge = (in_array(roletochar(intval($_SESSION['role'])), $badges) || in_array(strtolower(roletochar(intval($_SESSION['role']))), $badges));
			if (intval($_SESSION['role']) > 2)
			{
				// Gym Leader awarding badges
				$badges[$lastBadgeIndex] = roletochar(intval($_SESSION['role']));
				$badgeStr = implode($badges);
				$sqlAdd = ', earned_time = $3';
			}
			elseif (intval($_SESSION['role']) == 2)
			{
				// Criminal taking/returning badge
				if (!$trainerHasBadgeStolen)
				{
					// Badge theft
					$stolenBadgeIndex = rand(0,$lastBadgeIndex-1);
					$badges[$stolenBadgeIndex] = strtolower($badges[$stolenBadgeIndex]);
					$badgeStr = implode($badges);
				}
				else
				{
					// Badge return
					$badges[$stolenBadgeIndex] = strtoupper($badges[$stolenBadgeIndex]);
					$badgeStr = implode($badges);
				}
			}
			
			// Attempt to upload changes to DB
			$link = db_connect();
			
			// If the connection is valid, get ready to insert
			if (db_verify_conn($link))
			{
				// Prepare the SQL string
				$sql = 'UPDATE trainers set badges = $2'.$sqlAdd.' WHERE studentid = $1';
				
				// Execute the string
				$result = false;
				if (intval($_SESSION['role']) > 2 && !$alreadyHasBadge)
				{
					db_exec($link, $sql, trim($_GET['trainerID']), $badgeStr, date('Y-m-d H:i'));
				}
				elseif (intval($_SESSION['role']) == 2)
				{
					db_exec($link, $sql, trim($_GET['trainerID']), $badgeStr);
				}
				
				// Disconnect from the DB
				db_disconnect($link);
			}
		}
	}
	
	// If there is no trainerID specified in the URL, pick a random one
	if (!isset($_GET['trainerID']))
	{
		// Include necessary functions
		require_once('../../scripts/db-operations.php');
		
		// Connect to the DB
		$link = db_connect();
		
		// If connection success, pull ALL trainer IDs
		if (db_verify_conn($link))
		{
			// Select ALL IDs, and store them in an array
			$result = db_select($link, 'SELECT studentid FROM trainers WHERE studentid <> $1', '1234');
			
			// Disconnect from the DB
			db_disconnect($link);
			
			// If the array contains values, pick a random one
			if (count($result) > 0)
			{
				$_GET['trainerID'] = $result[rand(0,count($result)-1)]['studentid'];
			}
		}
		
		// $_GET['trainerID'] = $value;
	}
	
	$trainerHasBadgeStolen = false;
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
				
				// Include badge manipulation functions
				require_once('../../scripts/badge-conversions.php');
				
				// If the trainer's role is 3 or above, spawn a badge on the front of the card
				if (intval($result[0]['role']) >= 3)
				{
					$gymLeaderBadgeElem = role_strtobadge(strval($result[0]['role']));
				}
				
				// Take the badge string from the DB, and convert it into badges
				$badges = str_split(strval($result[0]['badges']));
				$lastBadgeIndex = 0;
				foreach ($badges as $badge)
				{
					if ($badge != '0')
					{
						$badgeElements .= strtobadge($badge);
					}
					if (ctype_lower($badge))
					{
						$trainerHasBadgeStolen = true;
					}
					$lastBadgeIndex++;
				}
				
				// If the trainer already has the Gym Leader's badge, lock the Badge Button
				if (in_array(roletochar(intval($_SESSION['role'])), $badges) || in_array(strtolower(roletochar(intval($_SESSION['role']))), $badges))
				{
					$displayBadgeBtn = false;
				}
				
				// (Figure out the timeout period, and lock the badge button accordingly)
				$start_date = new DateTime($result[0]['earned_time']);
				$since_start = $start_date->diff(new DateTime(date('Y-m-d H:i')));
				$total_minutes = $since_start->days *24*60 + $since_start->h *60 + $since_start->i;
				if ($total_minutes < 20)
				{
					$displayBadgeBtn = false;
				}
				
				// If the trainer is a Gym Leader, lock the Badge Button
				if (intval($result[0]['role']) > 2)
				{
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
			<p id="successText">'.$successText.'</p>
			<p id="errorText">'.$errorText.'</p>
		</form>';
	}
	$badgeButton = '';
	if ($displayBadgeBtn)
	{
		$badgeBtnText = '';
		if (intval($_SESSION['role']) == 2)
		{
			$badgeBtnText = 'Steal Badge';
			if ($trainerHasBadgeStolen)
			{
				$badgeBtnText = 'Return Badge';
			}
		}
		else
		{
			$badgeBtnText = 'Award Badge';
		}
		$badgeButton = '<form id="badge-form" action="./'.$postLink.'" method="post">
			<input type="hidden" name="role" value="123"/>
			<input type="submit" value="'.$badgeBtnText.'"/>
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
			<path class="menu-line" d="M500 500Q300 650 445 800"/>
			<line class="menu-line" x1="500" y1="500" x2="750" y2="725"/>
			<circle id="map-btn" class="menu-btn" cx="225" cy="650" r="75" onClick="location.href='../region-map';"/>
			<image class="menu-btn-img" x="-2" y="628" width="100" height="100" xlink:href="../../images/menu-icons/map.svg"/>
			<circle id="login-btn" class="menu-btn" cx="675" cy="150" r="75" onClick="location.href='../<?php echo $btnText;?>';"/>
			<image class="menu-btn-img" x="572" y="270" width="100" height="100" xlink:href="../../images/menu-icons/<?php echo $btnText;?>.svg"/>
			<circle id="register-btn" class="menu-btn" cx="750" cy="725" r="75" onClick="location.href='../registration';"/>
			<image class="menu-btn-img" x="497" y="845" width="100" height="100" xlink:href="../../images/menu-icons/registration.svg"/>
			<circle id="home-btn" class="menu-btn" cx="445" cy="800" r="55" onClick="location.href='../../';"/>
			<image class="menu-btn-img" x="183" y="845" width="80" height="80" xlink:href="../../images/menu-icons/home.svg"/>
			<circle id="gyms-btn" class="menu-btn" cx="250" cy="325" r="95" onClick="location.href='../gym-leaders';"/>
			<image class="menu-btn-img" x="95" y="330" width="130" height="130" xlink:href="../../images/menu-icons/gym.svg"/>
			<circle id="rules-btn" class="menu-btn" cx="800" cy="400" r="95" onClick="location.href='../rulebook';"/>
			<image class="menu-btn-img" x="605" y="527" width="130" height="130" xlink:href="../../images/menu-icons/book.svg"/>
			<circle id="profile-btn" class="menu-btn" cx="500" cy="500" r="135" onClick="location.href='../trainer-card<?php echo $trainerURL;?>';"/>
			<image class="menu-btn-img" x="265" y="525" width="180" height="180" xlink:href="../../images/menu-icons/id-card.svg"/>
			<text class="menu-btn-text" x="410" y="670">Trainer Card</text>
			<text class="menu-btn-text" x="155" y="455">Gym Leaders</text>
			<text class="menu-btn-text" x="140" y="760">Region Map</text>
			<text class="menu-btn-text" x="735" y="530">Rulebook</text>
			<?php echo $btnElem;?>
			<text class="menu-btn-text" x="630" y="835">Registration Mode</text>
			<text class="menu-btn-text" x="400" y="885">Home</text>
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
				<?php echo $gymLeaderBadgeElem;?>
			</div>
			<div class="card-back">
				<div class="card-back-overlay"></div>
				<p class="card-back-text">Badges</p>
				<?php echo $badgeElements;?>
			</div>
		</div>
	</div>
	<?php echo $cardCustomization;?>
 </body>
 </html>