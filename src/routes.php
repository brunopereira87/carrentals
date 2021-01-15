<?php
use core\Router;

$router = new Router();



$prefix = '/auth';

$router->post("$prefix/register","AuthController@register");
$router->post("$prefix/login","AuthController@login");
$router->get("$prefix/logged","AuthController@logged");

// $router->put("/users/edit","UserController@update");

// $prefix = '/cars';

// $router->get("$prefix",'CarController@read');
// $router->post("$prefix",'CarController@create');
// $router->get("$prefix/{id}",'CarController@read');
// $router->put("$prefix/{id}",'CarController@update');
// $router->put("$prefix/{id}/rent",'CarController@rent');
// $router->put("$prefix/{id}/return",'CarController@return');
// $router->delete("$prefix/{id}",'CarController@delete');