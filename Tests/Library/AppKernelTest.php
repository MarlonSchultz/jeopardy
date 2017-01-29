<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 29.01.17
 * Time: 12:44
 */

namespace Tests\Library;

use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\RouteCollection;

class AppKernelTest extends \PHPUnit_Framework_TestCase
{
    public function testIfKernelReturnsHttpKernelInterface()
    {
        $routeCollection = new RouteCollection();
        $appKernel = new AppKernel($routeCollection);
        self::assertTrue(is_a($appKernel->getKernel(), HttpKernel::class));
        self::assertInstanceOf(HttpKernelInterface::class, $appKernel->getKernel());
    }
}
