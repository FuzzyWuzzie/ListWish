<?

// get information about our item id
$item = array();
if($_GET['id'] == 'new')
{
	$item['name'] = '';
	$item['store'] = '';
	$item['website'] = '';
	$item['cost'] = '';
	$item['picture'] = '';
	$item['description'] = '';
}
else
{
	$result = mysqli_query($db, "select * from items where id='".$_GET['id']."'");
	$item = mysqli_fetch_array($result);
}

$drawPicture = "";
if(isset($item['picture']) && $item['picture'] != '')
{
	$drawPicture = <<<EOT
	<div class="row" style="margin-bottom: 1em;">
		<div class="small-12 large-4 large-centered text-centered columns">
			<img src="{$item['picture']}"/>
		</div>
	</div>
EOT;
}

$title = "Edit Item";
$contents = <<<EOT
<form data-abide action="?v=mylist&a=edititem&eid={$_GET['id']}" method="post" enctype='multipart/form-data'>
	<div class="row">
		<div class="large-12 columns">
			<label>Item Name</label>
			<input type="text" name="name" placeholder="{$item['name']}" value="{$item['name']}" pattern="alpha_numeric" />
		</div>
	</div>

	<div class="row">
		<div class="large-4 columns">
			<label>Store</label>
			<input type="text" name="store" placeholder="{$item['store']}" value="{$item['store']}" pattern="alpha_numeric" />
		</div>
		<div class="large-4 columns">
			<label>Website</label>
			<input type="text" name="website" placeholder="{$item['website']}" value="{$item['website']}" pattern="url" />
			<small class="error">A valid internet address (URL) is required.</small>
		</div>
		<div class="large-4 columns">
			<div class="row collapse">
				<label>Cost</label>
				<div class="small-1 columns">
					<span class="prefix">$</span>
				</div>
				<div class="small-11 columns">
					<input type="text" name="cost" placeholder="" value="" pattern="integer" />
				</div>
				<small class="error">A number is required.</small>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="large-12 columns">
			<label>Description</label>
			<textarea name="description" placeholder="{$item['description']}">{$item['description']}</textarea>
		</div>
	</div>

	<div class="row">
		<div class="large-12 columns">
			<label>Picture URL</label>
			<input type="text" name="pictureURL" placeholder="{$item['picture']}" value="{$item['picture']}" />
			<small class="error">A valid internet address (URL) is required.</small>
		</div>
	</div>

	<div class="row show-for-medium-up">
		<div class="large-12 columns">
			<label>Upload Picture</label>
			<input type='file' name='pictureUpload' />
		</div>
	</div>

	{$drawPicture}

	<div class="row">
		<div class="large-6 columns"><input class="success button expand" type="submit" value="Save" /></div>
		<div class="large-6 columns"><a href="?v=mylist" class="button expand alert">Cancel</a></div>
	</div>
</form>
EOT;

$script = <<<EOT
	// substitute input[file] with custom control
	$("input[type=file]").each(function() {
		var proxy = $('<input type="text" placeholder="Click to select image" value="'+$(this).val()+'" />');

		var context = {_this: $(this), _proxy: proxy};
		var intervalFunc = $.proxy(function() {
			this._proxy.val(this._this.val());
		}, context);

		// hide file input and watch for changes
		$(this)
			.css("position", "absolute")
			.css("opacity", "0.000001")
			.attr("size", "100")
			.parent().append(proxy)
			.click(function(event) {
				setInterval(intervalFunc, 1000);
			});
	});
EOT;

?>