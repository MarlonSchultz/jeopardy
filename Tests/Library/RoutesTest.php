<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 28.01.17
 * Time: 14:43
 */

namespace Tests\Library;

use mgbs\Library\Routes;
use Symfony\Component\Routing\RouteCollection;

class RoutesTest extends \PHPUnit_Framework_TestCase
{
      public function testIfRoutesAreReturnedCorrectly()
    {
        $routes = new Routes();
        self::assertTrue(is_a($routes->getRoutes(), RouteCollection::class));
    }
}
