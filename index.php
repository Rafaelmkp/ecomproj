<?php 

session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;
use \Ecomproj\Page;
use \Ecomproj\PageAdmin;
use \Ecomproj\Model\User;
	
$debug = 'index - debug antes $app';
var_dump($debug);

$app = new Slim();

$app->config('debug', true);

$debug = 'index - debug antes $app';
var_dump($debug);

require_once("./site.php");
require_once("admin2.php");
require_once("admin-users.php");
require_once("admin-categories.php");
require_once("admin-products.php");

$debug = 'index - debug apos requires';
var_dump($debug);


$app->run();

$debug = 'index - debug apos $app->run()';
var_dump($debug);

?>