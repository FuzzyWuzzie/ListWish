<?

// get our user's flags
$result = mysqli_query($db, "select email, flags from users where id='".$_SESSION['id']."'");
$row = mysqli_fetch_array($result);
$email = $row['email'];
$flags = explode(',', $row['flags']);
$enableNotifications = "";
$compactView = "";
if(in_array('email_notify', $flags))
	$enableNotifications = " checked";
if(in_array('compact_list_view', $flags))
	$compactView = " checked";

// and their secret santa information
$result = mysqli_query($db, "select users.name from users inner join secretSanta on users.id=secretSanta.giftee where secretSanta.id=".$_SESSION['id'].";");
$secretSanta = "";
if(mysqli_num_rows($result) > 0)
{
	$row = mysqli_fetch_array($result);
	$secretSanta = $row['name'];
}

$peopleOptions = "<option value='-1'></option>";
$result = mysqli_query($db, "select id, name from users where id != '" . $_SESSION['id'] . "' and not find_in_set('child', flags) and name != 'Unknown' order by name asc;");
while($row = mysqli_fetch_array($result))
	$peopleOptions .= "<option value='" . $row['id'] . "'" . ($secretSanta == $row['name'] ? ' selected' : '') . ">" . $row['name'] . "</option>";

$title = "Control Panel";
$contents = <<<EOT
<form id="controlPanelForm" data-abide>
	<input type="hidden" name="id" value="{$_SESSION['id']}"/>
	<h3>Secret Santa</h3>
	<p>Here you can store the name of who you're to buy a gift for for the secret santa gift exchange. This way if you ever forget, you can just come back here and check it. Don't worry, no one else will be able to see anyone else's secret santa name!</p>
	<div class="row">
		<div class="large-12 columns">
			<select name="secretSanta" id="secretSanta">
				{$peopleOptions}
			</select>
		</div>
	</div>

	<h3>Email Notifications</h3>
	<p>Here you may choose to receive email notifications whenever someone updates their list. This can be really handy to keep up to date with what's happening. Be careful, however. If someone updates their list a lot, you'll get a lot of emails notifying you of everything they changed on their list.</p>
	<div class="row">
		<div class="large-12 columns">
			<input id="notificationsCheckbox" name="notificationsCheckbox" type="checkbox"{$enableNotifications} value="yes"><label for="notificationsCheckbox">Enable email notifications</label>
		</div>
	</div>
	<div class="row">
		<div class="large-12 columns">
			<label>Email</label>
			<input type="email" id="email" name="email" placeholder="{$email}" value="{$email}" />
			<small class="error">A valid email address is required.</small>
		</div>
	</div>

	<h3>Change Your Password</h3>
	<p>You can change your password here. Note that it <b>is</b> case-sensitive, however there are no restrictions on the password. If you leave both fields blank, your password will not be changed.</p>
	<div class="row">
		<div class="large-12 columns">
			<label>New Password</label>
			<input id="password" name="password" type="password" placeholder="" value="" pattern="alpha_numeric" />
		</div>
	</div>
	<div class="row">
		<div class="large-12 columns">
			<label>New Password (Again)</label>
			<input id="passwordAgain" name="passwordAgain" type="password" placeholder="" value="" pattern="alpha_numeric" />
		</div>
	</div>

	<h3>Options</h3>
	<p>These are some various options that will change how you interact with the website. They're mostly cosmetic, so feel free to customize them to your liking!</p>
	<div class="row">
		<div class="large-12 columns">
			<input id="viewCompactCheckbox" name="viewCompactCheckbox" type="checkbox"{$compactView} value="yes"><label for="viewCompactCheckbox">View lists in a compact form</label>
		</div>
	</div>

	<hr/>

	<div class="row">
		<div class="large-6 columns"><a href="#" class="button expand success" data-reveal-id="savedModal">Save</a></div>
		<div class="large-6 columns"><a href="?v=mylist" class="button expand alert">Cancel</a></div>
	</div>
</form>

<div id="savedModal" class="reveal-modal" data-reveal>
  <p>Saving...</p>
</div>

EOT;

$script = <<<EOD
	$(document).on('open', '[data-reveal]', function(){
		formData = $("#controlPanelForm").serialize();
		$.ajax({
			url: 'actions/savecp.php',
			data: formData,
			type: 'POST',
			success: function(result) {
				$('#savedModal').html(result);
			}
		})
	});
EOD;

?>