<?php
define('DIR_CTRL', '..'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR);
define('DIR_CACHE', '..'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR);
define('DIR_ERRORS', '..'.DIRECTORY_SEPARATOR.'errors'.DIRECTORY_SEPARATOR);
define('DIR_LIB', '..'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR);
define('DIR_VIEW', '..'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR);

require_once DIR_CTRL.'autoload.php';

$currentPage = (isset($_GET['route']) && $_GET['route'] != "")?$_GET['route']:"index";
$currentPage = htmlentities(htmlspecialchars($currentPage));
$currentPage = explode(".", $currentPage)[0];

$router = new Router();
$data = $router->get($currentPage);

include DIR_VIEW . 'mainstruct.php';