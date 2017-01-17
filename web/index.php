<?php
declare(strict_types = 1);

/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 12.01.17
 * Time: 17:51
 */

require __DIR__ . '/../vendor/autoload.php';

use mgbs\Library\AppKernel;
use mgbs\Library\Routes;
use Symfony\Component\HttpFoundation\Request;



$request = Request::createFromGlobals();

$routes = new Routes();
$kernel = (new AppKernel($routes->getRoutes()))->getKernel();
\mgbs\Library\DI::setRoutes($routes->getRoutes());

$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);