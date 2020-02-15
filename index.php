<?php
	// Allocate variables for login/logout button
	$btnText = '';
	$btnElem = '';
	
	// Include necessary functions
	require_once('./scripts/login-verification.php');
	
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
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
 
 <meta http-equiv="Content-Type" content="text/html;   charset=utf-8"/>
 <meta name="keywords" content=""/>
 <meta name="description" content="<Description>"/>
 <meta name="author" content="<Author Name Here>"/>
 <meta name="viewport" content="width=device-width, initial-scale=1">

 <title>Tekiana League</title>
 
 <link rel="shortcut icon" href="path_to_icon"/>
 
 <link type="text/css" rel="stylesheet" href="stylesheets/style.css"/>
 <link type="text/css" rel="stylesheet" href="stylesheets/menu.css"/>
 <link type="text/css" rel="stylesheet" href="stylesheets/home.css"/>
 
 <link href="https://fonts.googleapis.com/css?family=Cabin|Roboto&display=swap" rel="stylesheet">
 <link href="https://fonts.googleapis.com/css?family=Dancing+Script|Great+Vibes&display=swap" rel="stylesheet">
	
 <script src="https://kit.fontawesome.com/b657a3b372.js" crossorigin="anonymous"></script>
 
 <script src="scripts/menu.js"></script>

 </head>
 <body>
	<div id="menu-show-btn" class="menu-btn" onClick="displayMenu();">
		<svg id="menu-close-icon" viewBox="0 0 100 100"><image x="20" y="20" width="60" height="60" xlink:href="images/menu-icons/menu.svg"/><svg/>
	</div>
	<div id="menu-overlay">
		<div id="menu-exit-btn" class="menu-btn" onClick="displayMenu();">
			<svg id="menu-close-icon" viewBox="0 0 100 100"><image x="20" y="20" width="60" height="60" xlink:href="images/menu-icons/close.svg"/><svg/>
		</div>
		<div id="menu-border"><div id="menu-bg"></div></div>
		<svg id="menu-buttons" viewBox="0 0 1000 1000">
			<line class="menu-line" x1="500" y1="500" x2="250" y2="325"/>
			<path class="menu-line" d="M500 500Q350 650 225 650"/>
			<path class="menu-line" d="M500 500Q700 550 800 400"/>
			<path class="menu-line" d="M500 500Q500 250 675 150"/>
			<line class="menu-line" x1="500" y1="500" x2="750" y2="725"/>
			<circle id="map-btn" class="menu-btn" cx="225" cy="650" r="75"/>
			<image class="menu-btn-img" x="-2" y="628" width="100" height="100" xlink:href="images/menu-icons/map.svg"/>
			<circle id="login-btn" class="menu-btn" cx="675" cy="150" r="75" onClick="location.href('pages/<?php echo $btnText;?>')"/>
			<image class="menu-btn-img" x="572" y="270" width="100" height="100" xlink:href="images/menu-icons/<?php echo $btnText;?>.svg"/>
			<circle id="register-btn" class="menu-btn" cx="750" cy="725" r="75"/>
			<image class="menu-btn-img" x="497" y="845" width="100" height="100" xlink:href="images/menu-icons/registration.svg"/>
			<circle id="gyms-btn" class="menu-btn" cx="250" cy="325" r="95"/>
			<image class="menu-btn-img" x="95" y="330" width="130" height="130" xlink:href="images/menu-icons/gym.svg"/>
			<circle id="rules-btn" class="menu-btn" cx="800" cy="400" r="95"/>
			<image class="menu-btn-img" x="605" y="527" width="130" height="130" xlink:href="images/menu-icons/book.svg"/>
			<circle id="profile-btn" class="menu-btn" cx="500" cy="500" r="135"/>
			<image class="menu-btn-img" x="265" y="525" width="180" height="180" xlink:href="images/menu-icons/id-card.svg"/>
			<text class="menu-btn-text" x="410" y="670">Trainer Card</text>
			<text class="menu-btn-text" x="155" y="455">Gym Leaders</text>
			<text class="menu-btn-text" x="140" y="760">Region Map</text>
			<text class="menu-btn-text" x="735" y="530">Rulebook</text>
			<?php echo $btnElem;?>
			<text class="menu-btn-text" x="630" y="835">Registration Mode</text>
		</svg>
	</div>
	<img id="league-logo" src="images/logos/league-logo.png" alt="League Logo"/>
	<h1>Tekiana League Gym Challenge</h1>
	<div class="content-div">
		<h2>Header 1</h2>
	</div>
	<div class="content-div">
		<h2>Header 2</h2>
	</div>
 </body>
 </html>