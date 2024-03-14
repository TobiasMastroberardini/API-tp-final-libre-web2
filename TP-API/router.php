<?php
require_once 'config.php';
require_once 'libs/router.php';
require_once './app/controllers/ReviewsApiController.php';
require_once './app/controllers/UserApiController.php';

$router = new Router();

$router->addRoute('reviews', 'GET', 'ReviewsApiController', 'get');
$router->addRoute('reviews/:ID', 'GET', 'ReviewsApiController', 'get');
$router->addRoute('reviews/:ID/:subrecurso', 'GET', 'ReviewsApiController', 'get');
$router->addRoute('reviews', 'POST', 'ReviewsApiController', 'create');
$router->addRoute('reviews/:ID', 'PUT', 'ReviewsApiController', 'update');
$router->addRoute('reviews/:ID', 'DELETE', 'ReviewsApiController', 'delete');
$router->addRoute('users/token', 'GET', 'UserApiController', 'getToken');


$router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);
