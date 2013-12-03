<?

$menu = array();
$menu['Dashboard'] = array('dashboard');
$menu['My List'] = array('mylist', 'edititem');
if(!in_array('child', $_SESSION['flags'])) $menu['View Lists'] = array('viewlists', 'viewlist');
if(!in_array('child', $_SESSION['flags'])) $menu['Shopping List'] = array('shoppinglist');
$menu['Control Panel'] = array('controlpanel');

?>