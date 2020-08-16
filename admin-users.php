<?php

use \Slim\Slim;
use \Ecomproj\PageAdmin;
use \Ecomproj\Model\User;

$app = new Slim();

$app->config('debug', true);

$app->get('/admin/users', function() 
{	
	User::verifyLogin();

	$user = User::listAll();

	$page = new PageAdmin();

	$page->setTpl("users", array(
		"users"=>$user
	));
});


$app->get('/admin/users/create', function() 
{	
	User::verifyLogin();
	
	$page = new PageAdmin();

	$page->setTpl("users-create");
});

$app->get('/admin/users/:iduser', function($iduser) 
{	
	User::verifyLogin();

	$user = new User();

	$user ->get((int)$iduser);

	$page = new PageAdmin();

	$page->setTpl("users-update", array(
		"user"=>$user->getValues()
	));
});

$app->post('/admin/users/create', function() 
{	
	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->setData($_POST);
	
	$user->save();

	header("Location: /admin/users");
	exit;
});

$app->get('/admin/users/:iduser/delete', function($iduser) 
{	
	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");
	exit;
	
});

$app->post('/admin/users/:iduser', function($iduser) 
{	
	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();

	header("Location: /admin/users");
	exit;	
});


?>