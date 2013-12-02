<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Log In | ListWish</title>

	<!-- If you are using CSS version, only link these 2 files, you may add app.css to use for your overrides if you like. -->
	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/foundation.css">

	<!-- If you are using the gem version, you need this only -->
	<link rel="stylesheet" href="css/app.css">

	<script src="js/vendor/custom.modernizr.js"></script>

	<style>
		#vc {
			position: absolute;
			left: 0;
			top: 0;
			width: 100%;
		}
	</style>
</head>
<body>

	<!-- Main Page Content -->
	<div id="vc">
		<form action="?a=login" method="POST">
			<div class="row">
				<div class="large-3 large-centered columns">
					<div class="row">
						<div class="large-12 column">
							<h1>ListWish</h1>
						</div>
					</div>

					<div class="row">
						<div class="large-12 columns<? if($loginError == 'badname') echo ' error'; ?>">
							<label for="name">Name</label>
							<input type="text" name="name" id="name" placeholder="Name"<? if(isset($loginName)) echo ' value="'.$loginName.'"';?>>
							<? if($loginError == 'badname') echo '<small>Invalid Name</small>'; ?>
						</div>
					</div>
					<div class="row">
						<div class="large-12 columns<? if($loginError == 'badpassword') echo ' error'; ?>">
							<label for="password">Password</label>
							<input type="password" name="password" id="password" placeholder="Password">
							<? if($loginError == 'badpassword') echo '<small>Invalid Password</small>'; ?>
						</div>
					</div>
					<div class="row">
						<div class="large-12 column">
							<input class="success button expand" type="submit" value="Log In" />
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
	<!-- End Main Content -->

	<script src="js/vendor/jquery.js"></script>
	<script src="js/foundation.min.js"></script>
	<script>
		$(document).foundation();

		$(window).resize(function() {
			var wh = (($(window).height()-$('#vc').height())/2)+'px';
			$('#vc').css({top: wh});
		}).resize();
	</script>
</body>
</html>