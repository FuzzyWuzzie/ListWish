<?

// make sure we have at least a name - everything else is optional
if(trim($_GET['did']) == '')
{
	$notifications[] = array("warning", "Your item was not deleted because it's identifier was not specified!");
	return 0;
}

// get our item information
$result = mysqli_query($db, "select * from items where id='".$_GET['did']."';");
$item = mysqli_fetch_array($result);

// and delete it!
$result = mysqli_query($db, "delete from items where id='".$_GET['did']."';");
if($result === FALSE)
{
	$errorMessage = mysqli_error($db);
	return 0;
}

// also delete the item from any shopping lists
$result = mysqli_query($db, "delete from shoppingLists where itemID='".$_GET['did']."';");
if($result === FALSE)
{
	$errorMessage = mysqli_error($db);
	return 0;
}

// notify by email
include('actions/emailnotify.php');
emailNotify($_SESSION['name'], 'delete', $item['name']);

$notifications[] = array("success", "'" . $item['name'] . "' was deleted!");

?>