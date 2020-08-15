<?php

use \Ecomproj\Page;

$debug = 'site - require worked';
var_dump($debug);

$app->get('/', function() 
{
	echo "app-get-site";	
	$page = new Page();

	$page->setTpl("index");
});

?>