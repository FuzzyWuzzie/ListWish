<?

if($_SESSION['loggedIn'] == 0 && (!isset($_GET['a']) || $_GET['a'] != 'login'))
{
	require('views/login.php');
	exit(0);
}
else if(isset($_GET['a']) && $_GET['a'] == 'login')
{
	$result = mysqli_query($db, "select * from users where lower(name)=lower('".$_POST['name']."') and password=PASSWORD('".$_POST['password']."') LIMIT 1");
	if(mysqli_num_rows($result) < 1)
	{
		$loginName = $_POST['name'];
		$loginError = "badpassword";
		// determine if it was a correct user name
		$result = mysqli_query($db, "select id from users where lower(name)=lower('".$_POST['name']."') LIMIT 1");
		if(mysqli_num_rows($result) < 1)
			$loginError = "badname";
		require('views/login.php');
		exit(0);
	}
	else
	{
		$_SESSION['loggedIn'] = True;
		$arr = mysqli_fetch_array($result);
		$_SESSION['id'] = $arr['id'];
		$_SESSION['name'] = $arr['name'];
		$_SESSION['email'] = $arr['email'];
		$_SESSION['flags'] = explode(',', $arr['flags']);
	}
}

?>