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
use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;
use Symfony\Component\Routing\RouteCollection;

class RoutesTest extends \PHPUnit_Framework_TestCase
{
    public function testIfRoutesAreReturnedCorrectly()
    {
        $routes = new Routes([__DIR__ . '/Fixtures'], 'routes.yml');
        self::assertTrue(is_a($routeCollection = $routes->getRoutes(), RouteCollection::class));

        self::assertArrayHasKey('_controller', $routeArray = $routeCollection->get('landing_page')->getDefaults());

        self::assertSame('mgbs\Controller\IndexController::indexAction', $routeArray['_controller']);
    }

    public function testIfExceptionIsThrownIfNoRoutesFileIsFound()
    {
        $this->expectException(FileLocatorFileNotFoundException::class);
        new Routes([__DIR__ . '/Fixtures'], 'noFileCanBeFound.yml');
    }
}
