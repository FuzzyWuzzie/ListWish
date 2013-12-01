<?

// get information about our item id
$item = array();
if($_GET['id'] == 'new')
{
	$item['name'] = '';
	$item['location'] = '';
	$item['description'] = '';
}
else
{
	$result = mysqli_query($db, "select * from items where id='".$_GET['id']."'");
	$item = mysqli_fetch_array($result);
}

$title = "Edit Item";
$contents = <<<EOT
<form data-abide>
	<div class="row">
		<div class="large-12 columns">
			<label>Item Name</label>
			<input type="text" placeholder="{$item['name']}" value="{$item['name']}" pattern="alpha_numeric" />
		</div>
	</div>

	<div class="row">
		<div class="large-4 columns">
			<label>Store</label>
			<input type="text" placeholder="{$item['location']}" value="{$item['location']}" pattern="alpha_numeric" />
		</div>
		<div class="large-4 columns">
			<label>Website</label>
			<input type="text" placeholder="" value="" pattern="url" />
			<small class="error">A valid internet address (URL) is required.</small>
		</div>
		<div class="large-4 columns">
			<div class="row collapse">
				<label>Cost</label>
				<div class="small-1 columns">
					<span class="prefix">$</span>
				</div>
				<div class="small-11 columns">
					<input type="text" placeholder="" value="" pattern="integer" />
				</div>
				<small class="error">A number is required.</small>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="large-12 columns">
			<label>Description</label>
			<textarea placeholder="{$item['description']}">{$item['description']}</textarea>
		</div>
	</div>

	<div class="row">
		<div class="large-6 columns"><a href="#" class="button expand">Save</a></div>
		<div class="large-6 columns"><a href="?v=mylist" class="button expand alert">Cancel</a></div>
	</div>
</form>
EOT;

?>