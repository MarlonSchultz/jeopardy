<?php
declare(strict_types = 1);
namespace mgbs\Library;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;

/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 16.01.17
 * Time: 19:32
 */
class Routes
{
    /** @var RouteCollection */
    private $routes;

    /**
     * Router constructor.
     * @throws \InvalidArgumentException
     */
    public function __construct()
    {
        $this->parseYml();
    }

    /**
     * @return RouteCollection
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function parseYml()
    {
        $locator = new FileLocator([__DIR__ . '/../Config']);
        $loader = new YamlFileLoader($locator);
        $collection = $loader->load('routes.yml');
        $this->routes = new RouteCollection();
        $this->routes->addCollection($collection);
    }
}
