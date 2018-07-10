<?php
declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

use mgbs\Library\AppKernel;
use mgbs\Library\DI;
use mgbs\Library\Routes;
use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();

$routes = new Routes();
$kernel = (new AppKernel($routes->getRoutes()))->getKernel();
DI::setRoutes($routes->getRoutes());
DI::getContainer()->set('request', $request);

$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);
