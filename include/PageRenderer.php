<?

class PageRenderer
{
	private $menu = array(
						'Dashboard' => array('dashboard'),
						'My List' => array('mylist', 'edititem'),
						'View Lists' => array('viewlists', 'viewlist'),
						'Control Panel' => array('controlpanel')
					);

	public function render(Page $page)
	{
		?><!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><? echo $page->pageTitle(); ?> | ListWish</title>

	<!-- If you are using CSS version, only link these 2 files, you may add app.css to use for your overrides if you like. -->
	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/foundation.css">

	<!-- If you are using the gem version, you need this only -->
	<link rel="stylesheet" href="css/app.css">

	<script src="js/vendor/custom.modernizr.js"></script>

	<style>
	<? if(in_array('masonry', $page->pageFlags())) { ?>
		.masonry-brick { width: 220px; margin: 10px; float: left; }
		.masrony-brick-center { width: 100%; text-align: center; margin-top: 1em; }
		.masrony-brick-center:first-of-type { margin-top: 0em; }
		.masrony-brick-center:last-of-type { margin-bottom: -1em; }
		#masonryContainer { width: 0 auto; }
	<? } ?>
		.side-nav { margin-top: -1em; }
		.selected-nav { background: #2ba6cb; }
		.selected-nav a { color: white; }
	</style>

	<? echo $page->pageStyle(); ?>

</head>
<body>

<? if(in_array('navigation', $page->pageFlags())) { ?>
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
					foreach($this->menu as $name => $slugs)
						echo '<li' . (in_array($page->pageSlug(), $slugs) ? ' class="active"' : '') . '><a href="?v=' . $slugs[0] . '"">' . $name . '</a></li>';
				?>
			</ul>
		</section>
	</nav>

	<!-- Mobile Navigation Bar -->
	<a href="#" data-dropdown="navdrop" class="button dropdown expand hide-for-medium-up"><? echo $page->pageTitle(); ?></a><br>
	<ul id="navdrop" data-dropdown-content class="f-dropdown hide-for-medium-up">
		<?

			foreach($this->menu as $name => $slugs)
				echo '<li' . (in_array($page->pageSlug(), $slugs) ? ' class="selected-nav"' : '') . '><a href="?v=' . $slugs[0] . '"">' . $name . '</a></li>';

		?>
		<li><a href="?v=logout">Log Out</a></li>
	</ul>
	<!-- End Mobile Navigation Bar -->
<? } ?>

<? if(in_array('custom contents', $page->pageFlags()))
{
	echo $page->pageContents();
}
else
{ ?>
	<!-- Main Page Content and Sidebar -->
	<div class="row">
		<!-- Main Content -->
		<div class="large-12 columns" role="content">
			<h1 class="hide-for-small-only"><? echo $page->pageTitle(); ?></h1>
			<? echo $page->pageContents(); ?>

		</div>
	</div>
	<!-- End Main Content -->
<? } ?>

	<script src="js/vendor/jquery.js"></script>
	<script src="js/foundation.min.js"></script>
	<? if(in_array('masonry', $page->pageFlags())) { ?>
	<script src="js/vendor/masonry.pkgd.min.js"></script>
	<? } ?>
	<script src="js/vendor/jquery.autosize.min.js"></script>
	<script>
		$(document).foundation();

		$(window).load(function(){
			<? if(in_array('masonry', $page->pageFlags())) { ?>
				$('#masonryContainer').masonry({  
					itemSelector: '.masonry-brick',
					columnWidth: 240
				});
			<? } ?>

			$('textarea').autosize();
		});   
	</script>
	<? echo $page->pageScript(); ?>
</body>
</html>
		<?
	}
}

?>