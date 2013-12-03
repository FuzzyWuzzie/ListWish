<?

if(!isset($_POST['id']))
	die("<div data-alert class='alert-box'><p>An unknown error occurred!</p></div>");

// load up our sql connection
$db = null;
$dbServer = "";
$dbUser   = "";
$dbPass   = "";
$dbDB     = "";
if((@include('../db.php')) === FALSE)
	die("<div data-alert class='alert-box'><p>Failed to load database settings!</p></div>");
else
{
	$db = @mysqli_connect($dbServer, $dbUser, $dbPass, $dbDB);
	if(mysqli_connect_errno())
		die("<div data-alert class='alert-box'><p>A database error occurred!</p><p>Please relay the following information to Kenton:</p><pre class='errorPre'>" . mysqli_error($db) . "</pre></div>");
}

// do the passwords
if(isset($_POST['password']) && $_POST['password'] != '')
{
	// we have a password request, make sure they match
	if($_POST['password'] != $_POST['passwordAgain'])
		die("<div data-alert class='alert-box'><p>The passwords you entered don't match! Please try again and make sure they match to continue.</p><p>Nothing was changed.</p></div>");

	// we must have a valid password, update it
	$result = mysqli_query($db, "update users set password=password('" . $_POST['password'] . "') where id='" . $_POST['id'] . "';");
	if($result === FALSE)
		die("<div data-alert class='alert-box'><p>A database error occurred!</p><p>Please relay the following information to Kenton:</p><pre class='errorPre'>" . str_replace($_POST['password'], '***', mysqli_error($db)) . "</pre></div>");
}

function array_remove($arr, $value) { 
	return array_values(array_diff($arr, array($value))); 
}

// do the secret santa
$result = True;
if($_POST['secretSanta'] == -1)
	$result = mysqli_query($db, "delete from secretSanta where id='" . $_POST['id'] . "';");
else
	$result = mysqli_query($db, "insert into secretSanta (id, giftee) values(" . $_POST['id'] . ", " . $_POST['secretSanta'] . ") on duplicate key update giftee=values(giftee);");
if($result === FALSE)
	die("<div data-alert class='alert-box'><p>A database error occurred!</p><p>Please relay the following information to Kenton:</p><pre class='errorPre'>" . mysqli_error($db) . "</pre></div>");

// get our current flags
$result = mysqli_query($db, "select flags from users where id='".$_POST['id']."'");
$row = mysqli_fetch_array($result);
$flags = explode(',', $row['flags']);

// do email notifications
if(isset($_POST['notificationsCheckbox']))
	$flags[] = 'email_notify';
else
	$flags = array_remove($flags, 'email_notify');

// do compact view
if(isset($_POST['viewCompactCheckbox']))
	$flags[] = 'compact_list_view';
else
	$flags = array_remove($flags, 'compact_list_view');

// do our flags
$flags = array_unique($flags);
$result = mysqli_query($db, "update users set flags='" . implode(',', $flags) . "' where id='".$_POST['id']."';");
if($result === FALSE)
	die("<div data-alert class='alert-box'><p>A database error occurred!</p><p>Please relay the following information to Kenton:</p><pre class='errorPre'>" . mysqli_error($db) . "</pre></div>");

// close our database
if($db) mysqli_close($db);

?>
<h3>Success!</h3>
<p>Your settings have been saved!</p>