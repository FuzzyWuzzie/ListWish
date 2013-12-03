<?

// make sure we have at least a name - everything else is optional
if(trim($_POST['name']) == '')
{
	$notifications[] = array("warning", "Your item was not " . ($_GET['eid'] == 'new' ? 'added' : 'edited') . " because it's name was empty!");
	return 0;
}

// figure out which id to use
$id = $_GET['eid'];
if($_GET['eid'] == 'new')
{
	$result = mysqli_query($db, "select max(id) from items;");
	$row = mysqli_fetch_row($result);
	$id = $row[0] + 1;
}

// sort out our picture situation
$pictureURL = $_POST['pictureURL'];
if($_FILES['pictureUpload']['name'] != '')
{
	// we have a file
	$uploadedfile = $_FILES['pictureUpload']['tmp_name'];
	$target = "images/" . basename($_FILES['pictureUpload']['name']);
	
	$types = array("image/gif", "image/jpeg", "image/pjpeg", "image/png");
	
	$error = false;
	if(!in_array($_FILES['pictureUpload']['type'], $types))
	{
		$notifications[] = array("warning", "Your picture <b>was not</b> uploaded! Please limit your file uploads to image files! (.bmp files <b>don't</b> count!)");
		$error = true;
	}
	elseif(!move_uploaded_file($_FILES['pictureUpload']['tmp_name'], $target))
	{
		$notifications[] = array("warning", "I failed to upload your image!");
		$error = true;
	}
	
	if(!$error)
	{
		include('actions/simpleimage.php');
		$image = new SimpleImage();
		$image->load($target);
		$image->resizeToWidth(400);
		$image->save($target);
		
		$pictureURL = $target;
	}
}

// go ahead and update it
$result = mysqli_query($db, "insert into items (id, name, store, website, cost, description, picture, addDate) values($id, '".mysqli_real_escape_string($db, $_POST['name'])."', '".mysqli_real_escape_string($db, $_POST['store'])."', '".mysqli_real_escape_string($db, $_POST['website'])."', '".mysqli_real_escape_string($db, $_POST['cost'])."', '".mysqli_real_escape_string($db, $_POST['description'])."', '".mysqli_real_escape_string($db, $pictureURL)."', CURRENT_TIMESTAMP) on duplicate key update name=values(name), store=values(store), website=values(website), cost=values(cost), description=values(description), picture=values(picture), addDate=values(addDate);");
if($result === FALSE)
{
	$errorMessage = mysqli_error($db);
	return 0;
}
$notifications[] = array("success", "Your item was " . ($_GET['eid'] == 'new' ? 'added' : 'edited') . "!");

// notify by email
include('actions/emailnotify.php');
emailNotify($_SESSION['name'], $_GET['eid'] == 'new' ? 'add' : 'edit', $_POST['name']);

?>