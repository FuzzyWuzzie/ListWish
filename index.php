<?

// get the requested view, defaulting to dashboard
$view = 'dashboard';
if(isset($_GET['v']))
	$view = $_GET['v'];

// load up our sql connection
$db = null;
if((@include('db.php')) === FALSE)
{
	$errorMessage = "Failed to load database settings!";
	$view = "internalerror";
}
else
{
	$db = @mysqli_connect();
	if(mysqli_connect_errno())
		$view = "dberror";
}

// get the current view's contents
$title = "";
$contents = "";
if((@include('views/' . $view . '.php')) === FALSE)
	require('views/404.php');

// todo: parse contents

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
	</style>

</head>
<body>

	<nav class="top-bar hide-for-small-only" data-topbar>
		<ul class="title-area">
			<li class="name">
				<h1><a href="?v=dashboard">ListWish</a></h1>
			</li>
		</ul>

		<section class="top-bar-section">
			<!-- Right Nav Section -->
			<ul class="right">
				<li><a href="?v=logout">Log Out</a></li>
			</ul>

			<!-- Left Nav Section -->
			<ul class="left">
				<li<? if($view == 'dashboard') echo ' class="active"'; ?>><a href="?v=dashboard">Dashboard</a></li>
				<li<? if($view == 'mylist') echo ' class="active"'; ?>><a href="?v=mylist">My List</a></li>
				<li<? if($view == 'viewlists') echo ' class="active"'; ?>><a href="?v=viewlists">View Lists</a></li>
				<li<? if($view == 'controlpanel') echo ' class="active"'; ?>><a href="?v=controlpanel">Control Panel</a></li>
			</ul>
		</section>
	</nav>

	<!-- Mobile Navigation Bar -->
	<a href="#" data-dropdown="navdrop" class="button dropdown expand hide-for-medium-up"><? echo $title; ?></a><br>
	<ul id="navdrop" data-dropdown-content class="f-dropdown hide-for-medium-up">
		<li><a href="?v=dashboard">Dashboard</a></li>
		<li><a href="?v=mylist">My List</a></li>
		<li><a href="?v=viewlists">View Lists</a></li>
		<li><a href="?v=controlpanel">Control Panel</a></li>
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