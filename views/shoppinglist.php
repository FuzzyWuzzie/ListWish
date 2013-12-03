<?

$title = "Shopping List";
$contents = "";

// get all of the items on our list
$previousName = "";
$result = mysqli_query($db, "select users.name as username, items.id, items.name, items.store, items.website, items.cost, mod(aes_decrypt(items.purchased, users.name), 2)=1 as purchased from shoppingLists inner join items on shoppingLists.itemID=items.id inner join users on items.owner=users.id where shoppingLists.owner=" . $_SESSION['id'] . " order by users.name asc;");
$numRecords = mysqli_num_rows($result);
while($row = mysqli_fetch_array($result))
{
	if($row['username'] != $previousName)
	{
		// if this isn't the first name, end the last name group
		if($previousName != '')
		{
			$contents .= "</div></div>";
		}

		// start a new name group
		$contents .= '<div class="row"><div class="small-12 large-4 large-centered columns panel"><h3>' . $row['username'] . '</h3><hr/>';

		// set our previous name
		$previousName = $row['username'];
	}

	$contents .= '<div class="row"><div class="small-2 column slitem' . ($row['purchased'] == 1 ? ' slchecked' : '') . '" id="check-' . $row['id'] . '"><a href="' . ('?v=shoppinglist&a=markaspurchased&pid=' . $row['id'] . '&p=' . ($row['purchased'] == 1 ? 'no' : 'yes') . '&giftee=' . $row['username'] . '&gifter=' . $_SESSION['id'] . '#item-' . $row['id']) . '"><i class="fi-check"></i></a></div><div class="small-10 column slitem' . ($row['purchased'] == 1 ? ' slpurchased' : '') . '" id="item-' . $row['id'] . '">' . $row['name'];
	if($row['store'] != '' || $row['cost'] != '' || $row['website'] != '')
	{
		$contents .= '<ul class="sldetails">';
		if($row['cost'] != '' || $row['store'] != '')
		{
			$contents .= '<li>';
			if($row['cost'] != '')
				$contents .= '$' . $row['cost'];
			if($row['store'] != '')
				$contents .= ($row['cost'] == '' ? 'From ' : ' from ') . $row['store'];
			$contents .= '</li>';
		}
		if($row['website'] != '')
			$contents .= '<li><a href="' . $row['website'] . '">' . $row['website'] . '</a>';
		$contents .= '</ul>';
	}
	$contents .=  '</div></div>';
}

// if we had at least one result, end the group
if($numRecords > 0)
{
	$contents .= "</div></div>";
}
// otherwise, tell the user they should add some things to their list
else
{
	$contents .= '<div data-alert class="alert-box info">You don\'t have anything on your shopping list yet! Take a look at some peoples\' lists to start building your shopping list!<a href="#" class="close">&times;</a></div>';
}

?>