<?

// first, connect to mysql
$db = mysqli_connect("mysql.hamaluik.com", "kenton_content", "092288", "kenton_christmas");

// USERS
/*$result = mysqli_query($db, "select * from users;");
while($row = mysqli_fetch_array($result))
{
	echo "insert into users(name, password, email, flags) values(";
	echo "'" . $row['name'] . "', ";
	echo "'" . $row['pass'] . "', ";
	echo "'" . $row['email'] . "', ";
	echo "'";
	$flags = array();
	if($row['enablenotifications'] == 'yes')
		$flags[] = 'email_notify';
	if($row['child'] == '1')
		$flags[] = 'child';
	echo implode(', ', $flags);
	echo "'";
	echo ");\r\n";
}

echo "\r\n";*/

$userMapping = array(
	1 => 1,
	2 => 2,
	3 => 3,
	4 => 4,
	5 => 5,
	6 => 6,
	7 => 7,
	8 => 8,
	9 => 9,
	10 => 10,
	11 => 11,
	12 => 12,
	13 => -1,
	14 => 13,
	15 => 14,
	16 => -1,
	17 => -1,
	18 => -1,
	19 => 15,
	20 => -1,
	21 => 16,
	22 => 17,
	23 => 18,
	24 => 19,
	25 => 20,
	28 => 21,
	29 => 22
);

function randomPurchasedValue($purchased)
{
	$r = rand();
	if($purchased)
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

function randomTime()
{
	return mktime(1, 2, 3, rand(1, 12), rand(1, 28), rand(1970, 1979));
}

// ITEMS
//$result = mysqli_query($db, "select * from items;");
$result = mysqli_query($db, 'select items.owner, items.name, items.where, items.cost, items.description, items.picture, items.purchased, users.name as username from items inner join users on users.id=items.owner where items.name != "";');
while($row = mysqli_fetch_array($result))
{
	if($userMapping[$row['owner']] == -1 || $row['name'] == '')
		continue;
	echo "insert into items(owner, name, store, website, cost, description, picture, number, purchased, purchaser, purchaseDate) values(";
	echo "'" . $userMapping[$row['owner']] . "', ";
	echo "'" . mysqli_real_escape_string($db, $row['name']) . "', ";
	if(strpos($row['where'], 'http') !== false)
		echo "'', '" . mysqli_real_escape_string($db, $row['where']) . "', ";
	else
		echo "'" . mysqli_real_escape_string($db, $row['where']) . "', '', ";
	echo "'" . mysqli_real_escape_string($db, str_replace('$', '', $row['cost'])) . "', ";
	echo "'" . mysqli_real_escape_string($db, $row['description']) . "', ";
	echo "'" . mysqli_real_escape_string($db, $row['picture']) . "', ";
	echo "'0', ";
	echo "aes_encrypt('".randomPurchasedValue($row['purchased'] == 'yes')."', '".$row['username']."'), ";
	echo "aes_encrypt('23', '".$row['username']."'), ";
	echo "aes_encrypt('".randomTime()."', '".$row['username']."')";
	echo ");\r\n";
}

/*echo "\r\n";

// SECRET SANTA
$result = mysqli_query($db, "select id,secretsanta from users where secretsanta!='';");
while($row = mysqli_fetch_array($result))
{
	$secretsanta = base64_decode($row['secretsanta']);
	$result2 = mysqli_query($db, "select id from users where name like '".$secretsanta."' limit 1;");
	$row2 = mysqli_fetch_array($result2);
	echo "insert into secretSanta(owner, giftee) values(".$userMapping[$row['id']].", ".$userMapping[$row2['id']].");\r\n";
}*/

?>