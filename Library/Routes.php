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
     * @var array
     */
    private $fileLocatorPath;
    /**
     * @var string
     */
    private $routingFile;

    /**
     * Router constructor.
     * @param array $fileLocatorPath
     * @param string $routingFile
     * @throws \InvalidArgumentException
     */
    public function __construct(array $fileLocatorPath = [__DIR__ . '/../Config'], string $routingFile = 'routes.yml')
    {
        $this->fileLocatorPath = $fileLocatorPath;
        $this->routingFile = $routingFile;
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
        $locator = new FileLocator($this->fileLocatorPath);
        $loader = new YamlFileLoader($locator);
        $collection = $loader->load($this->routingFile);
        $this->routes = new RouteCollection();
        $this->routes->addCollection($collection);
    }
}
