<?php

use \Ecomproj\PageAdmin;
use \Ecomproj\Model\User;
use \Ecomproj\Model\Category;
use \Ecomproj\Model\Product;

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


?>