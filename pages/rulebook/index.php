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
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
 
 <meta http-equiv="Content-Type" content="text/html;   charset=utf-8"/>
 <meta name="keywords" content=""/>
 <meta name="description" content="<Description>"/>
 <meta name="author" content="<Author Name Here>"/>
 <meta name="viewport" content="width=device-width, initial-scale=1">

 <title>Tekiana League Rules</title>
 
 <link rel="shortcut icon" href="../../images/site-icons/gym-logo.png"/>
 
 <link type="text/css" rel="stylesheet" href="../../stylesheets/style.css"/>
 <link type="text/css" rel="stylesheet" href="../../stylesheets/menu.css"/>
 <link type="text/css" rel="stylesheet" href="../../stylesheets/trainer-card.css"/>
 <link type="text/css" rel="stylesheet" href="../../stylesheets/rulebook.css"/>
 
 <link href="https://fonts.googleapis.com/css?family=Cabin|Roboto&display=swap" rel="stylesheet">
 <link href="https://fonts.googleapis.com/css?family=Dancing+Script|Great+Vibes&display=swap" rel="stylesheet">
	
 <script src="https://kit.fontawesome.com/b657a3b372.js" crossorigin="anonymous"></script>
 
 <script src="../../scripts/menu.js"></script>

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
	<h1>Tekiana League Rules</h1><br/>
	<div id="content">
		<h2>General Rules</h2>
		<p>
			<ul>
				<li>All Pokémon will be lowered to a cap of Level 50 when competing in League matches.</li>
				<li>League matches will be fought using Local Communication.</li>
				<li>Participants' teams may not contain more than one Pokémon with the same Pokédex number.</li>
				<li>There are to be no duplicate items held by participating Pokémon</li>
				<li>Participants will be allowed to use Pokémon from any region, provided they exist in the Galar Pokédex.</li>
				<li>Participants will not be allowed to use legendary Pokémon in any League match.</li>
				<li>Participants who are challenged by another Trainer are obligated to accept the challenge, under the terms specified by the challenging Trainer.</li>
				<ul>
					<li>This is to keep the League's atmosphere lively, and promote healthy rivalries between Trainers.</li>
				</ul>
			</ul>
		</p>
		<h2>Challenger Rules</h2>
		<p>
			<ul>
				<li>Challengers must register to compete in the Tekiana League</li>
				<ul>
					<li>Registration will take place during the week preceding the League, and will continue throughout the duration of the League.</li>
					<li>When registering, students will present their student ID, and specify a password for their League account.</li>
				</ul>
				<li>Challengers must collect at least 8 Gym Badges from challenging Gym Leaders in order to qualify for the League Championship tournament at the end of the League.</li>
				<ul>
					<li>Challengers will have approximately 2 weeks to collect as many Gym Badges as possible.</li>
				</ul>
			</ul>
		</p>
		<h2>Gym Leader Rules</h2>
	</div>
 </body>
 </html>