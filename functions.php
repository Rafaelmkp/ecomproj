<?php

use \Ecomproj\Model\User;


function formatPrice($vlPrice)
{
    if(!$vlprice > 0) $vlprice = 0;
    
    return number_format($vlPrice, 2, ",", ".");        
}

function checkLogin($inadmin = true)
{
    return User::checkLogin($inadmin);
}

function getUserName()
{
    $user = User::getFromSession();

    return $user->getdesperson();
}

?>