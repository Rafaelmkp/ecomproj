<?php 

session_start();
require_once("vendor/autoload.php");
require_once("functions.php");

use \Slim\Slim;

$app = new Slim();

$app->config('debug', true);

require_once("site.php");
require_once("admin2.php");
require_once("admin-categories.php");
require_once("admin-products.php");
require_once("admin-users.php");

$app->run();

?>