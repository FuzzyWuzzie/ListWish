<?

// define where classes are loaded from
function __autoload($className)
{
	if(strpos($className, 'Page_') === 0)
		require('include/pages/' . substr($className, 5) . '.php');
	else
		require('include/' . $className . '.php');
}

// "minimize" the html that gets output
require('include/utilities/MinimizeHTML.php');

// create our page
$loginPage = new Page_LoginForm();

$renderer = new PageRenderer();
$renderer->render($loginPage);

?>