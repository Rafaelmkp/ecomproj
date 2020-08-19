<?php 

session_start();
require_once("vendor/autoload.php");
require_once("functions.php");

use \Slim\Slim;
use \Ecomproj\Page;
use \Ecomproj\PageAdmin;
use \Ecomproj\Model\User;
use \Ecomproj\Model\Category;
use \Ecomproj\Model\Product;

$app = new Slim();

$app->config('debug', true);

//site
$app->get('/', function() 
{
	$products = Product::listAll();

	$page = new Page();

	$page->setTpl("index", [
		'products'=>Product::checklist($products)
	]);
});

$app->get("/categories/:idcategory", function($idcategory){


	$category = new Category();

	$category->get((int)$idcategory);

	$page = new Page();

	$page->setTpl("category",[
		'category'=>$category->getValues(),
		'products'=>Product::checkList($category->getProducts())
	]);

});
//end site

//admin2
$app->get('/admin', function() 
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
	header("Location: /admin");
	exit;

});

$app->get('/admin/logout', function() {
	User::logout();

	header("Location: /admin/login");
	exit;
});

$app->get('/admin/forgot', function() 
{
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot");

});

$app->post('/admin/forgot', function()
{
	$user = User::getForgot($_POST["email"]);

	header("Location: /admin/forgot/sent");
	exit;
});

$app->get('/admin/forgot/sent', function()
{
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot-sent");

});

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
//end admin

//admin-categories
$app->get("/admin/categories", function()
{	
	User::verifyLogin();

	$categories = Category::listAll();
	$page = new PageAdmin();

	$page->setTpl("categories", [
		"categories"=>$categories
	]);
});

$app->get("/admin/categories/create", function()
{	
	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("categories-create");
});

$app->post("/admin/categories/create", function()
{	
	User::verifyLogin();

	$category = new Category();

	$category->setData($_POST);	

	$category->save();

	header("Location: /admin/categories");
	exit;
});

$app->get("/admin/categories/:idcategory/delete", function($idcategory)
{	
	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);	
	
	$category->delete();
	var_dump($category);
	header("Location: /admin/categories");
	exit;
});

$app->get("/admin/categories/:idcategory", function($idcategory)
{	
	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new PageAdmin();

	$page->setTpl("categories-update", [
		"category"=>$category->getValues()
	]);
});

$app->post("/admin/categories/:idcategory", function($idcategory)
{	
	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$category->setData($_POST);

	$category->save();

	header("Location: /admin/categories");
	exit;
});

$app->get("/admin/categories/:idcategory/products", function($idcategory){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new PageAdmin();

	$page->setTpl("categories-products",[
		'category'=>$category->getValues(),
		'productsRelated'=>$category->getProducts(),
		'productsNotRelated'=>$category->getProducts(false)
	]);
});

$app->get("/admin/categories/:idcategory/products/:idproduct/add", function($idcategory, $idproduct){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$product = new Product();

	$product->get((int)$idproduct);

	$category->addProduct($product);

	header("Location: /admin/categories/".$idcategory."/products");
	exit;
});

$app->get("/admin/categories/:idcategory/products/:idproduct/remove", function($idcategory, $idproduct){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$product = new Product();

	$product->get((int)$idproduct);

	$category->removeProduct($product);

	header("Location: /admin/categories/".$idcategory."/products");
	exit;
});
//end admin-categories

//admin-products
$app->get("/admin/products", function(){

    User::verifyLogin();

    $products = Product::listAll();

	$page = new PageAdmin();

	$page->setTpl("products", [
        "products"=>$products
    ]);
});

$app->get("/admin/products/create", function(){

    User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("products-create");
});

$app->post("/admin/products/create", function(){

    User::verifyLogin();

	$product = new Product();

	$product->setData($_POST);

	header("Location: /admin/products");
	exit;
});

$app->get("/admin/products/:idproduct", function($idproduct){

    User::verifyLogin();

	$product = new Product();

	$product->get((int)$idproduct);

	$page = new PageAdmin();

	$page->setTpl("products-update", [
		'product'=>$product->getValues()
	]);
});

$app->post("/admin/products/:idproduct", function($idproduct){

    User::verifyLogin();

	$product = new Product();

	$product->get((int)$idproduct);

	$product->setData($_POST);

	$product->save();

	if ((int)$_FILES["file"]["size"] > 0) 
        $product->setPhoto($_FILES["file"]);

	header("Location: /admin/products");
	exit;
});

$app->get("/admin/products/:idproduct", function($idproduct){

    User::verifyLogin();

	$product = new Product();

	$product->get((int)$idproduct);

	$product->delete();

	header("Location: /admin/products");
	exit;
});
//end admin-products

//admin-users
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

//admin-users
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


$app->run();

?>