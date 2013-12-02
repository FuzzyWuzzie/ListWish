<?

// start our session and make sure we're logged in
session_start();
if(!isset($_SESSION['loggedIn']))
	$_SESSION['loggedIn'] = False;

// let us log out
if(isset($_GET['v']) && $_GET['v'] == 'logout')
{
	$_SESSION['loggedIn'] = False;
	$_SESSION['name'] = '';
	$_SESSION['email'] = '';
	$_SESSION['flags'] = array();
}

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
if($_SESSION['loggedIn'] == 0 && (!isset($_GET['a']) || $_GET['a'] != 'login'))
{
	require('views/login.php');
	exit(0);
}
else if(isset($_GET['a']) && $_GET['a'] == 'login')
{
	$result = mysqli_query($db, "select * from users where lower(name)=lower('".$_POST['name']."') and password=PASSWORD('".$_POST['password']."') LIMIT 1");
	if(mysqli_num_rows($result) < 1)
	{
		$loginName = $_POST['name'];
		$loginError = "badpassword";
		// determine if it was a correct user name
		$result = mysqli_query($db, "select id from users where lower(name)=lower('".$_POST['name']."') LIMIT 1");
		if(mysqli_num_rows($result) < 1)
			$loginError = "badname";
		require('views/login.php');
		exit(0);
	}
	else
	{
		$_SESSION['loggedIn'] = True;
		$arr = mysqli_fetch_array($result);
		$_SESSION['name'] = $arr['name'];
		$_SESSION['email'] = $arr['email'];
		$_SESSION['flags'] = explode(',', $arr['flags']);
	}
}

// get the requested view, defaulting to dashboard
$view = 'dashboard';
if(isset($_GET['v']))
	$view = $_GET['v'];

// get the current view's contents
$title = "";
$contents = "";
if((@include('views/' . $view . '.php')) === FALSE)
	require('views/404.php');

// load up our menu information
require('menu.php');

?><!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><? echo $title; ?> | ListWish</title>

	<!-- If you are using CSS version, only link these 2 files, you may add app.css to use for your overrides if you like. -->
	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/foundation.css">

	<!-- If you are using the gem version, you need this only -->
	<link rel="stylesheet" href="css/app.css">

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
			<? echo $contents; ?>

		</div>
	</div>
	<!-- End Main Content -->

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
	</script>
</body>
</html>