<?

$title = "View Lists";
$contents = <<<EOD
<p>Select a person below to view their list:</p>
<div class="row">
	<div class="large-12 columns">
		<div id="masonryContainer">
EOD;

// get all our items
$result = mysqli_query($db, "select * from users where name != 'Unknown' order by name asc;");
while($row = mysqli_fetch_array($result))
{
	$contents .= '<div class="masonry-brick panel"><a href="?v=viewlist&id=' . $row['id'] . '" class="button expand">' . $row['name'] . '</a></div>';
}

$contents .= <<<EOD
		</div>
	</div>
</div>
EOD;

?>