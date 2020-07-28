<?php 

require_once("vendor/autoload.php");

use \Slim\Slim;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() 
{	
	$page = new Page();

	$page->setTpl("index");

	var_dump($_SERVER["DOCUMENT_ROOT"]);
});

$app->run();

 ?>
