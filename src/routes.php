<?php
use core\Router;

$router = new Router();



$prefix = '/auth';

$router->post("$prefix/register","AuthController@register");
$router->post("$prefix/login","AuthController@login");
$router->get("$prefix/logged","AuthController@logged");

$prefix = '/cars';

$router->post("$prefix",'CarsController@create');
$router->get("$prefix",'CarsController@read');
$router->get("$prefix/{id}",'CarsController@read');
$router->put("$prefix/{id}",'CarsController@update');
$router->delete("$prefix/{id}",'CarsController@delete');
$router->put("$prefix/{id}/rent",'CarsController@rent');
$router->put("$prefix/{id}/return",'CarsController@return');