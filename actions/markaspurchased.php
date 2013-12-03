<?

if(!isset($_GET['pid']))
{
	$errorMessage = "An unknown error occurred!";
	return 0;
}

function randomPurchasedValue($purchased)
{
	$r = rand();
	if($purchased == "yes")
	{
		if($r % 2 != 1)
			$r--;
	}
	else
	{
		if($r %2 != 0)
			$r--;
	}
	return $r;
}

// update it in the database!
$result = mysqli_query($db, "update items set purchased=aes_encrypt('".randomPurchasedValue($_GET['p'])."', '".$_GET['giftee']."'), purchaser=aes_encrypt('".$_GET['gifter']."', '".$_GET['giftee']."'), purchaseDate=aes_encrypt('".time()."', '".$_GET['giftee']."') where id='" . $_GET['pid'] . "';");
if($result === FALSE)
{
	$errorMessage = mysqli_error($db);
	return 0;
}

$notifications[] = array("success", "Your item was marked as purchased!");

?>