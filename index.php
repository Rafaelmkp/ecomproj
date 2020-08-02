<?php 

session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;
use \Ecomproj\Page;
use \Ecomproj\PageAdmin;
use \Ecomproj\Model\User;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() 
{	
	$page = new Page();

	$page->setTpl("index");
});

$app->get('/admin/', function() 
{	
	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("index");
});

$app->get('/admin/login', function() 
{	
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("login");
});

//validacao do login eh realizada por metodo post
$app->post('/admin/login', function() 
{
	User::login($_POST["login"],$_POST["password"]);

	//se login validado com sucesso, redireciona 
	header("Location: /admin/");
	exit;

});

$app->get('/admin/logout', function() {
	User::logout();

	header("Location: /admin/login");
	exit;
});
$app->run();

 ?>
