<?

function emailNotify($person, $action, $itemName)
{
	// send out notification emails
	global $db;
	$result = mysqli_query($db, "select email from users where find_in_set('email_notify', flags) and id != '".$_SESSION['id']."'");

	// for making proper grammar
	$actions = array(
		"add" => "added",
		"edit" => "changed",
		"delete" => "deleted"
	);

	$blarghs = array(
		"add" => "to",
		"edit" => "on",
		"delete" => "from"
	);

	// build the message
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: ListWish AutoMailer <listwish@hamaluik.com>' . "\r\n" .	'Reply-To: listwish@hamaluik.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
	$subject = $person . " Updated Their List on ListWish!";

	$message = "<html><head><title>" . $subject . "</title></head><body>";
	$message = '<p>' . $person . ' <u>' . $actions[$action] . '</u> item \'<b>' . $itemName . '</b>\' ' . $blarghs[$action] . ' their list!';
	$message .= " To their updated list, log in to the ListWish website at <a href='http://listwish.hamaluik.com'>http://listwish.hamaluik.com</a>.</p>";
	$message .= "\r\n\r\n<p>This is an automated email. If you would like to disable these notifications, please change the appropriate options in the control panel of the <a href='http://listwish.hamaluik.com'>ListWish website.</a></p></body></html>";

	// collect our emails
	$emails = array();
	while($user = mysqli_fetch_array($result))
		$emails[] = $user['email'];

	// remove any duplicate emails
	$emails = array_unique($emails);

	// and send an email to each user!
	foreach($emails as $email)
		mail($email, $subject, $message, $headers);
}

?>