<?php
declare(strict_types = 1);

/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 12.01.17
 * Time: 17:51
 */

use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../vendor/autoload.php';


$request = Request::createFromGlobals();

$kernel = new AppKernel();

$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);