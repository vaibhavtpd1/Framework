<?php
session_start();

// Project root path constant
define('ROOTPATH', dirname(__FILE__));

// Autoload methods
require_once(ROOTPATH . '/core/bootstrap.php');

$router = new Router();
$router->run();
