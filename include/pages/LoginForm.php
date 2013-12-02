<?

class Page_LoginForm extends Page
{
	public function pageSlug() { return "login"; }
	public function pageFlags() { return array('custom contents'); }
	public function pageTitle() { return "Log In"; }
	public function pageContents()
	{
		$contents = <<<EOD
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
							<div class="large-12 columns">
								<label for="name">Name</label>
								<input type="text" name="name" id="name" placeholder="Name">
							</div>
						</div>
						<div class="row">
							<div class="large-12 columns">
								<label for="password">Password</label>
								<input type="password" name="password" id="password" placeholder="Password">
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
EOD;
		return $contents;
	}

	public function pageStyle()
	{
		return <<<EOD
			<style>
				#vc {
					position: absolute;
					left: 0;
					top: 0;
					width: 100%;
				}
			</style>
EOD;
	}

	public function pageScript()
	{
		return <<<EOD
			<script>
				$(window).resize(function() {
					var wh = (($(window).height()-$('#vc').height())/2)+'px';
					$('#vc').css({top: wh});
				}).resize();
			</script>
EOD;
	}
}

?>