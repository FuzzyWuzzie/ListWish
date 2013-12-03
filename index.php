<?

// minimize our html
//@include('minimizeHTML.php');

// set our error handling function
$notifications = array();
$errorMessage = "";
function errorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    }

	$errorLevels = array(
		2047 => 'E_ALL',
		1024 => 'E_USER_NOTICE',
		512 => 'E_USER_WARNING',
		256 => 'E_USER_ERROR',
		128 => 'E_COMPILE_WARNING',
		64 => 'E_COMPILE_ERROR',
		32 => 'E_CORE_WARNING',
		16 => 'E_CORE_ERROR',
		8 => 'E_NOTICE',
		4 => 'E_PARSE',
		2 => 'E_WARNING',
		1 => 'E_ERROR');

	global $errorMessage;
    $errorMessage .= "\r\n\r\n" . $errorLevels[$errno] . ": $errstr\r\nIn file '" . basename($errfile) . "' at line '$errline'";
    $errorMessage = trim($errorMessage);
    return true;
}
set_error_handler("errorHandler");

// start our session and make sure we're logged in
session_start();
if(!isset($_SESSION['loggedIn']))
	$_SESSION['loggedIn'] = False;

// let us log out
if(isset($_GET['v']) && $_GET['v'] == 'logout')
{
	$_SESSION['loggedIn'] = False;
	$_SESSION['id'] = '';
	$_SESSION['name'] = '';
	$_SESSION['email'] = '';
	$_SESSION['flags'] = array();
}

// get the requested view, defaulting to dashboard
$view = 'dashboard';
if(isset($_GET['v']))
	$view = $_GET['v'];

// load up our sql connection
$db = null;
$dbServer = "";
$dbUser   = "";
$dbPass   = "";
$dbDB     = "";
if((@include('db.php')) === FALSE)
{
	$errorMessage = "Failed to load database settings!";
	$view = "internalerror";
}
else
{
	$db = @mysqli_connect($dbServer, $dbUser, $dbPass, $dbDB);
	if(mysqli_connect_errno())
		$view = "dberror";
}

// log in if we need to
require('login.php');

// deal with any actions
if(isset($_GET['a']) && $_GET['a'] != 'login')
{
	if((include('actions/'.$_GET['a'].'.php')) === FALSE)
	{
		$errorMessage = "Failed to include action '" . $_GET['a'] . "'!";
		$view = "internalerror";
	}
}

// get the current view's contents
$title = "";
$contents = "";
if((include('views/' . $view . '.php')) === FALSE)
	require('views/404.php');

// load up our menu information
require('menu.php');

// close our database
if($db) mysqli_close($db);

?><!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><? echo $title; ?> | ListWish</title>
	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/foundation.css">
	<link rel="stylesheet" href="css/foundation-icons.css">
	<!--<link rel="stylesheet" href="css/app.css">-->
	<script src="js/vendor/custom.modernizr.js"></script>

	<style>
		.masonry-brick { width: 220px; margin: 10px; float: left; }
		.masrony-brick-center { width: 100%; text-align: center; margin-top: 1em; }
		.masrony-brick-center:first-of-type { margin-top: 0em; }
		.masrony-brick-center:last-of-type { margin-bottom: -1em; }
		#masonryContainer { width: 0 auto; }
		.side-nav { margin-top: -1em; }
		.selected-nav { background: #2ba6cb; }
		.selected-nav a { color: white; }
		.purchased { background: #fff; }
		.purchased li, .purchased p, .purchased a, .purchased a:hover, .purchased a:active, .purchased a:visited { color: #ccc; }
		.errorPre { border: 1px solid white; color: black; background: white; }
		.sldetails { list-style-type: none; margin-bottom: 0; }
		.sldetails li { line-height: 110%; }
		.slitem { margin-bottom: 1em; }
		.slitem a, .slitem a:active, .slitem a:visited { color: #e9e9e9; }
		.slitem a:hover { color: #2ba6cb; }
		.slchecked a, .slchecked a:active, .slchecked a:visited { color: #5da423; }
		.slchecked a:hover { color: #e9e9e9; }
		.slpurchased { text-decoration: line-through; }
	</style>

</head>
<body>

	<nav class="top-bar hide-for-small-only" data-topbar>
		<ul class="title-area">
			<li class="name">
				<h1><a href="?">ListWish</a></h1>
			</li>
		</ul>

		<section class="top-bar-section">
			<!-- Right Nav Section -->
			<ul class="right">
				<li><a href="?v=logout">Log Out</a></li>
			</ul>

			<!-- Left Nav Section -->
			<ul class="left">
				<?
					foreach($menu as $name => $slugs)
						echo '<li' . (in_array($view, $slugs) ? ' class="active"' : '') . '><a href="?v=' . $slugs[0] . '"">' . $name . '</a></li>';
				?>
			</ul>
		</section>
	</nav>

	<!-- Mobile Navigation Bar -->
	<a href="#" data-dropdown="navdrop" class="button dropdown expand hide-for-medium-up"><? echo $title; ?></a><br>
	<ul id="navdrop" data-dropdown-content class="f-dropdown hide-for-medium-up">
		<?
			foreach($menu as $name => $slugs)
				echo '<li' . (in_array($view, $slugs) ? ' class="selected-nav"' : '') . '><a href="?v=' . $slugs[0] . '"">' . $name . '</a></li>';
		?>
		<li><a href="?v=logout">Log Out</a></li>
	</ul>
	<!-- End Mobile Navigation Bar -->

	<!-- Main Page Content and Sidebar -->
	<div class="row">
		<!-- Main Content -->
		<div class="large-12 columns" role="content">
			<h1 class="hide-for-small-only"><? echo $title; ?></h1>
			<? if ($errorMessage != '') { ?>
				<div data-alert class="alert-box alert">
					<p>An error occurred, if you see Kenton, show him this:</p>
					<pre class="errorPre"><? echo $errorMessage; ?></pre>
				</div>
			<? } ?>
			<?
			foreach($notifications as $notification)
			{
				$type = $notification[0];
				$message = $notification[1];
				?>
					<div data-alert class="alert-box <? echo $type; ?>">
						<? echo $message; ?>
						<a href="#" class="close">&times;</a>
					</div>
				<?
			}
			?>
			<? echo $contents; ?>

		</div>
	</div>
	<!-- End Main Content -->

	<!-- Footer -->
	<footer class="row">
		<div class="large-12 columns text-center">
			<p>&copy; 2013-2014 <a href="mailto:kenton@hamaluik.com">Kenton Hamaluik</a></p>
		</div>
	</footer>
	<!-- End Footer -->

	<script src="js/vendor/jquery.js"></script>
	<script src="js/foundation.min.js"></script>
	<script src="js/vendor/masonry.pkgd.min.js"></script>
	<script src="js/vendor/jquery.autosize.min.js"></script>
	<script>
		$(document).foundation();

		$(window).load(function(){
			$('#masonryContainer').masonry({  
				itemSelector: '.masonry-brick',
				columnWidth: 240
			});

			$('textarea').autosize();
		});

		<? echo $script; ?>
	</script>
</body>
</html>