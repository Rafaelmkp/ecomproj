<?php

use \Ecomproj\PageAdmin;
use \Ecomproj\Model\User;

//show users page
$app->get('/admin/users', function() 
{	
	User::verifyLogin();

	$user = User::listAll();

	$page = new PageAdmin();

	$page->setTpl("users", array(
		"users"=>$user
	));
});

//create user page
$app->get('/admin/users/create', function() 
{	
	User::verifyLogin();
	
	$page = new PageAdmin();

	$page->setTpl("users-create");
});

//create user function route
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

//update user page
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

//update user function route
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

//delete user function route
$app->get('/admin/users/:iduser/delete', function($iduser) 
{	
	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");
	exit;
});

?>