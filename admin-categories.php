<?php

use \Ecomproj\PageAdmin;
use \Ecomproj\Model\User;
use \Ecomproj\Model\Category;

//admin categories page
$app->get("/admin/categories", function()
{	
	User::verifyLogin();

	$categories = Category::listAll();
	$page = new PageAdmin();

	$page->setTpl("categories", [
		"categories"=>$categories
	]);
});

//create category page
$app->get("/admin/categories/create", function()
{	
	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("categories-create");
});

//create category function route
$app->post("/admin/categories/create", function()
{	
	User::verifyLogin();

	$category = new Category();

	$category->setData($_POST);	

	$category->save();

	header("Location: /admin/categories");
	exit;
});

//delete category function route
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

//update category page
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

//update category function route
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

//list products in x category page
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

//add x product x category function/page
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

//remove x product x category function/page
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

?>  