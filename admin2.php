<?php

use \Ecomproj\PageAdmin;
use \Ecomproj\Model\User;

$app->get('/admin', function() 
{	
	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("index");
});

//login page
$app->get('/admin/login', function() 
{	
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("login");
});

//login validation in post method
$app->post('/admin/login', function() 
{
	User::login($_POST["login"],$_POST["password"]);

	//if successful validation, redirects
	header("Location: /admin");
	exit;

});

//logout function
$app->get('/admin/logout', function() {
	User::logout();

	//redirects to login page
	header("Location: /admin/login");
	exit;
});

//forgot password page
$app->get('/admin/forgot', function() 
{
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot");

});

//forgot password function route
$app->post('/admin/forgot', function()
{
	$user = User::getForgot($_POST["email"]);

	header("Location: /admin/forgot/sent");
	exit;
});

//forgot password email sent page
$app->get('/admin/forgot/sent', function()
{
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot-sent");

});

//reset password page
$app->get("/admin/forgot/reset", function () 
{
	$user = User::validForgotDecrypt($_GET["code"]);

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot-reset", array(
		"name"=>$user["desperson"],
		"code"=>$_GET["code"]
	));
});

//reset password function route
$app->post("/admin/forgot/reset", function () 
{
	$forgot = User::validForgotDecrypt($_POST["code"]);

	User::setForgotUsed($forgot["idrecovery"]);

	$user = new User();

	$user->get((int)$forgot["iduser"]);

	$password = password_hash($_POST["password"], PASSWORD_DEFAULT, [
		"cost"=>12
	]);

	$user->setPassword($password);

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot-reset-success");
});




?>