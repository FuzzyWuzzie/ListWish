<?

$result = mysqli_query($db, "select name, flags from users where id=" . $_GET['id']);
$row = mysqli_fetch_array($result);
$listName = $row['name'];

$result = mysqli_query($db, "select flags from users where id=" . $_SESSION['id']);
$row = mysqli_fetch_array($result);
$userFlags = explode(',', $row['flags']);

$title = $listName . "'s List";
if(in_array('compact_list_view', $userFlags))
{
	$contents = <<<EOD
<div class="row">
  <div class="large-12 columns">
    <div id="masonryContainer">
EOD;
}
else
	$contents = "";

function abbreviate($text, $maxLength)
{
	if (strlen($text) >= $maxLength)
		return substr($text, 0, $maxLength). "...";
	return $text;
}

function sanitizeDescription($string, $length = -1)
{
	$string = str_replace(array('<p>', '</p>', '<br>', '<br />'), '', $string);
	$string = preg_replace('/((www|http:\/\/)[^ ]+)/', '<a href="\1">\1</a>', $string);
	$string = '<p class="text-justify">'.preg_replace(array("/([\n]{2,})/i", "/([^>])\n([^<])/i"), array("</p>\n<p>", '$1<br />$2'), trim($string)).'</p>';
	if($length >= 0)
		return abbreviate($string, $length);
	return $string;
}

function normalizeImage($img)
{
	if(strpos($img, "http") === 0)
		return $img;
	return "http://listwish.hamaluik.com/" . $img;
}

$result = mysqli_query($db, 'select items.id, items.name, items.picture, items.cost, items.store, items.website, items.number, items.description, users.name as purchaser, AES_DECRYPT(items.purchased, "' . $listName . '") as purchased, AES_DECRYPT(items.purchaseDate, "' . $listName . '") as purchaseDate, shoppingLists.id as slid from items inner join users on AES_DECRYPT(items.purchaser, "' . $listName . '")=users.id left join shoppingLists on shoppingLists.itemID=items.id where items.owner=' . $_GET['id'] .' and (shoppingLists.owner='.$_SESSION['id'].' or shoppingLists.owner is null) order by items.id desc;');
while($row = mysqli_fetch_array($result))
{
	$purchased = $_GET['id'] != $_SESSION['id'] && $row['purchased'] % 2 == 1;
	$onShoppingList = !is_null($row['slid']);

	$contents .= '<div class="' . (in_array('compact_list_view', $userFlags) ? 'masonry-brick ' : '') . 'panel' . ($purchased ? ' purchased' : '') . '">';
	$contents .= '<a id="item-' . $row['id'] . '"></a>';

	if($purchased)
	{
		$contents .= '<h3><i class="fi-check"></i> <s>' . $row['name'] . '</s></h3>';
		if($row['purchaser'] == 'Unknown')
			$contents .= '<h4>This item has already been purchased!</h4>';
		else
		{
			$contents .= '<h4>This item was purchased by ' . $row['purchaser'] . ' on ' . date('F j, Y', $row['purchaseDate']) . '.</h4>';
		}
	}
	else
		$contents .= '<h3>' . $row['name'] . '</h3>';

	if(!$purchased && $row['picture'] != '')
	{
		$contents .= '<div class="row">';
		if(in_array('compact_list_view', $userFlags))
			$contents .= '	<div class="small-12 small-centered columns text-center">';
		else
			$contents .= '	<div class="small-8 large-4 small-centered columns text-center">';
		$contents .= '		<img src="' . normalizeImage($row['picture']) . '" />';
		$contents .= '	</div>';
		$contents .= '</div>';
	}

	$contents .= '<ul>';
	if($row['cost'] != '')
		$contents .= '<li>Cost: $' . $row['cost'] . '</li>';

	if($row['store'] != '')
		$contents .= '<li>Store: ' . $row['store'] . '</li>';

	if($row['website'] != '')
	{
		if(in_array('compact_list_view', $userFlags))
			$contents .= '<li><a href="' . $row['website'] . '">Website</a></li>';
		else
			$contents .= '<li>Website: <a href="' . $row['website'] . '">' . abbreviate($row['website'], 30) . '</a></li>';
	}

	if($row['number'] != 0)
		$contents .= '<li>Number Requested: ' . $row['number'] . '</li>';
	$contents .= '</ul>';

	if($row['description'] != '')
		$contents .= sanitizeDescription($row['description'], in_array('compact_list_view', $userFlags) ? 100 : -1);

	if($_GET['id'] != $_SESSION['id'] && !$purchased)
	{
		// if it's not already been purchased and we're not looking at our own page, show a button to mark it as purchased
		if(in_array('compact_list_view', $userFlags))
		{
			$contents .= '<div class="row">';
			$contents .= '	<div class="small-12 column">';
			$contents .= '		<a href="?v=viewlist&id=' . $_GET['id'] . '&a=markaspurchased&pid=' . $row['id'] . '&p=yes&giftee=' . $listName . '&gifter=' . $_SESSION['id'] . '#item-' . $row['id'] . '" class="button success expand">Mark As Purchased</a>';
			$contents .= '	</div>';
			$contents .= '</div>';
			$contents .= '<div class="row" style="margin-bottom: -1em;">';
			$contents .= '	<div class="small-12 column">';
			if($onShoppingList)
				$contents .= '		<a href="?v=viewlist&id=' . $_GET['id'] . '&a=addtoshoppinglist&s=no&sid=' . $row['id'] . '#item-' . $row['id'] . '" class="button expand alert">Remove from Shopping List</a>';
			else
				$contents .= '		<a href="?v=viewlist&id=' . $_GET['id'] . '&a=addtoshoppinglist&s=yes&sid=' . $row['id'] . '#item-' . $row['id'] . '" class="button expand">Add To Shopping List</a>';
			$contents .= '	</div>';
			$contents .= '</div>';
		}
		else
		{
			$contents .= '<div class="row" style="margin-bottom: -1em;">';
			$contents .= '	<div class="small-6 column">';
			$contents .= '		<a href="?v=viewlist&id=' . $_GET['id'] . '&a=markaspurchased&pid=' . $row['id'] . '&p=yes&giftee=' . $listName . '&gifter=' . $_SESSION['id'] . '#item-' . $row['id'] . '" class="button success expand">Mark As Purchased</a>';
			$contents .= '	</div>';
			$contents .= '	<div class="small-6 column">';
			if($onShoppingList)
				$contents .= '		<a href="?v=viewlist&id=' . $_GET['id'] . '&a=addtoshoppinglist&s=no&sid=' . $row['id'] . '#item-' . $row['id'] . '" class="button expand alert">Remove from Shopping List</a>';
			else
				$contents .= '		<a href="?v=viewlist&id=' . $_GET['id'] . '&a=addtoshoppinglist&s=yes&sid=' . $row['id'] . '#item-' . $row['id'] . '" class="button expand">Add To Shopping List</a>';
			$contents .= '	</div>';
			$contents .= '</div>';
		}
	}
	else if($_GET['id'] != $_SESSION['id'] && $purchased && $row['purchaser'] == $_SESSION['name'])
	{
		// if it's been purchased by us, show a button to unmark it as purchased
		$contents .= '<div class="row" style="margin-bottom: -1em;">';
		$contents .= '	<div class="small-12 column">';
		$contents .= '		<a href="?v=viewlist&id=' . $_GET['id'] . '&a=markaspurchased&pid=' . $row['id'] . '&p=no&giftee=' . $listName . '&gifter=' . $_SESSION['id'] . '#item-' . $row['id'] . '" class="button alert expand">Unmark As Purchased</a>';
		$contents .= '	</div>';
		$contents .= '</div>';
	}

	$contents .= '</div>';
}

if(in_array('compact_list_view', $userFlags))
{
	$contents .= <<<EOD
    </div>
  </div>
</div>
EOD;
}

?>