<?php

use \Ecomproj\Page;
use \Ecomproj\Model\Category;
use \Ecomproj\Model\Product;
use \Ecomproj\Model\Cart;

//index page
$app->get('/', function() 
{
	$products = Product::listAll();

	$page = new Page();

	$page->setTpl("index", [
		'products'=>Product::checklist($products)
	]);
});

//x category products page
$app->get("/categories/:idcategory", function($idcategory){

	$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

	$category = new Category();

	$category->get((int)$idcategory);

	$pagination = $category->getProductsPage($page);
	
	$pages = [];

	//calculates how many product pages in x category
	for($i= 1; $i <= $pagination['pages']; $i++) {
		array_push($pages, [
			'link'=>'/categories/'.$category->getidcategory().'?page='.$i,
			'page'=>$i
		]);
	}

	$page = new Page();

	$page->setTpl("category",[
		'category'=>$category->getValues(),
		'products'=>$pagination["data"],
		'pages'=>$pages
	]);
});

$app->get('/products/:desurl', function($desurl) 
{
	$product = new Product();

	$product->getFromUrl($desurl);

	$page = new Page();

	$page->setTpl("product-detail", [
		'product'=>$product->getValues(),
		'categories'=>$product->getCategories()
	]);
});

$app->get('/cart', function()
{
	$cart = Cart::getFromSession();

	$page = new Page();

	$page->setTpl("cart");
})
?>