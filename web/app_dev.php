<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#checking-symfony-application-configuration-and-setup
// for more information
//umask(0000);

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.
/*if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !(in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1')) || php_sapi_name() === 'cli-server')
) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}*/
$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
Debug::enable();

require_once __DIR__.'/../app/AppKernel.php';

define('UPLOAD_DIR',  __DIR__ . "/uploads");
define('UPLOAD_PICTOGRAMS_DIR',  __DIR__ . "/uploads/pictograms/");
define('UPLOAD_PICTOGRAMS_URL',  "uploads/pictograms/");
define('UPLOAD_MATTERS_DIR',  __DIR__ . "/uploads/matters/");
define('UPLOAD_MATTERS_URL',  "uploads/matters/");
define('UPLOAD_FIXING_DIR',  __DIR__ . "/uploads/fixing/");
define('UPLOAD_FIXING_URL',  "uploads/fixing/");
define('UPLOAD_TECHNICALS_DIR',  __DIR__ . "/uploads/technicals/");
define('UPLOAD_TECHNICALS_URL',  "uploads/technicals/");
define('UPLOAD_PICTOGRAMS_CATEGORIES_DIR',  __DIR__ . "/uploads/pictograms_categories/");
define('UPLOAD_PICTOGRAMS_CATEGORIES_URL',  "uploads/pictograms_categories/");
define('UPLOAD_ORDER_ITEM_DIR',  __DIR__ . "/uploads/order_items/");
define('UPLOAD_ORDER_ITEM_URL',  "uploads/order_items/");

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
