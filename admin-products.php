<?php

use \Ecomproj\PageAdmin;
use \Ecomproj\Model\User;
use \Ecomproj\Model\Category;
use \Ecomproj\Model\Product;

//admin products page
$app->get("/admin/products", function(){

    User::verifyLogin();

    $products = Product::listAll();

	$page = new PageAdmin();

	$page->setTpl("products", [
        "products"=>$products
    ]);
});

//create product page 
$app->get("/admin/products/create", function(){

    User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("products-create");
});

//create product function route
$app->post("/admin/products/create", function(){

    User::verifyLogin();

	$product = new Product();

	$product->setData($_POST);

	header("Location: /admin/products");
	exit;
});

//update product page
$app->get("/admin/products/:idproduct", function($idproduct){

    User::verifyLogin();

	$product = new Product();

	$product->get((int)$idproduct);

	$page = new PageAdmin();

	$page->setTpl("products-update", [
		'product'=>$product->getValues()
	]);
});

//update product function route
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

//delete product function route
$app->get("/admin/products/:idproduct", function($idproduct){

    User::verifyLogin();

	$product = new Product();

	$product->get((int)$idproduct);

	$product->delete();

	header("Location: /admin/products");
	exit;
});

?>