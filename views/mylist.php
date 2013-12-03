<?

$title = "Your List";
$contents = <<<EOD
<p>Below you can add items to your list, view what's in your list, and edit or delete those items in your list.</p>
<a href="?v=edititem&id=new" class="button expand">Add New Item</a>
<div class="row">
	<div class="large-12 columns">
		<div id="masonryContainer">
EOD;

function normalizeImage($img)
{
	if(strpos($img, "http") === 0)
		return $img;
	return "http://listwish.hamaluik.com/" . $img;
}

function abbreviateDescription($description)
{
	if (strlen($description) >= 100)
		return substr($description, 0, 100). "...";
	return $description;
}

// get all our items
$result = mysqli_query($db, "select * from items where owner='" . $_SESSION['id'] ."'");
while($row = mysqli_fetch_array($result))
{
	$contents .= '<div class="masonry-brick panel">';
		$contents .= '		<h5>' . $row['name'] . '</h5>';
		if($row['cost'] != '') $contents .= '		<h6>Cost: $' . $row['cost'] . '</h6>';
		if($row['description'] != '') $contents .= '		<p>' . abbreviateDescription($row['description']) . '</p>';
		if($row['picture'] != '') $contents .= '		<div class="masrony-brick-center"><img src="' . normalizeImage($row['picture']) . '"></div>';
		$contents .= '		<div class="masrony-brick-center"><a href="?v=edititem&id=' . $row['id'] . '" class="button small">Edit</a> <a href="#" onClick="setDeleteItem('.$row['id'].', \''.$row['name'].'\');" data-reveal-id="confirmModal" class="button small alert">Delete</a></div>';
		$contents .= '	</div>';
	}

$contents .= <<<EOD
		</div>
	</div>
</div>

<div id="confirmModal" class="reveal-modal" data-reveal>
	<p>Are you sure you want to delete item '<span id="deleteName"></span>'?</p>
	<div class="row">
		<div class="small-12 large-6 column">
			<a id="yesDeleteLink" href="?v=mylist&a=deleteitem&did=-1" class="button alert expand">Yes</a>
		</div>
		<div class="small-12 large-6 column">
			<a href="#" class="button expand" onClick="$('#confirmModal').foundation('reveal', 'close');">No</a>
		</div>
	</div>
	<a class="close-reveal-modal">&#215;</a>
</div>

EOD;

$script = <<<EOD
	function setDeleteItem(id, name)
	{
		$("#deleteName").text(name);
		$("#yesDeleteLink").attr("href", "?v=mylist&a=deleteitem&did=" + id);
	}
EOD;

?>