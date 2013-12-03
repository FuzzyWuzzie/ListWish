<?

if(!isset($_GET['sid']))
{
	$errorMessage = "An unknown error occurred!";
	return 0;
}

if($_GET['s'] == 'yes')
{
	// only add it to the list if we didn't already have it on there
	$result = mysqli_query($db, "select count(id) from shoppingLists where owner=" . $_SESSION['id'] . " and itemID=" . $_GET['sid'] . ";");
	$row = mysqli_fetch_row($result);
	if($row[0] > 0)
	{
		$notifications[] = array("warning", "That item was already on your shopping list!");
		return 0;
	}

	// ok, it wasn't on there. go ahead and add it
	$result = mysqli_query($db, "insert into shoppingLists(owner, itemID) values(".$_SESSION['id'].", ".$_GET['sid'].");");
	if($result === FALSE)
	{
		$errorMessage = mysqli_error($db);
		return 0;
	}

	$notifications[] = array("success", "That item was added to your shopping list!");
}
else
{
	// delete it!
	$result = mysqli_query($db, "delete from shoppingLists where owner=" . $_SESSION['id'] . " and itemID=" . $_GET['sid'] . ";");
	if($result === FALSE)
	{
		$errorMessage = mysqli_error($db);
		return 0;
	}

	$notifications[] = array("success", "That item was removed from your shopping list!");
}

?>