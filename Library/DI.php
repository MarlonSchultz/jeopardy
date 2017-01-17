<?php
/**
 * @author dknx01 <e.witthauer@gmail.com>
 * @since 17.01.17 20:50
 */

namespace mgbs\Library;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class DI
{
    /**
     * @var ContainerInterface
     */
    private static $container;

    /**
     * @var RouteCollection
     */
    private static $routes;

    private function __construct()
    {
        $this->initDI();
        $this->loadParameters();
        $this->initTwig();
        if (self::$routes) {
            self::$container->setParameter('routes', self::$routes);
        }
    }

    private function initDI()
    {
        self::$container = new ContainerBuilder();
        $loader = new YamlFileLoader(self::$container, new FileLocator(__DIR__ . '/../Config/'));
        $loader->load('services.yml');
    }

    /**
     * @throws \Exception
     */
    private function loadParameters()
    {
        if (!file_exists(__DIR__ . '/../Config/parameters.yml')) {
            return;
        }
        try {
            $configFile = Yaml::parse(file_get_contents(__DIR__ . '/../Config/parameters.yml'));
            foreach ($configFile as $key => $value) {
                self::$container->setParameter($key, $value);
            }
        } catch (ParseException $e) {
            throw new \Exception('Unable to parse the parameters.yml file: ' . $e->getMessage());
        }
    }

    private function initTwig()
    {
        $loader = new \Twig_Loader_Filesystem(array(__DIR__ . '/../View'));
        $twig = new \Twig_Environment($loader, ['cache' => __DIR__ . '/../Var/Cache/Twig']);
        $twig->addExtension(new \Twig_Extension_Debug());
        $twig->addExtension(new \Twig_Extension_StringLoader());
        $twig->addExtension(new \Twig_Extensions_Extension_Array());
        $twig->addExtension(new \Twig_Extensions_Extension_Date());
        $twig->addExtension(new \Twig_Extensions_Extension_Text());
        if (self::$routes) {
            $twig->addExtension(new RoutingExtension(new UrlGenerator(self::$routes, new RequestContext())));
        }
        if (self::$container->hasParameter('twig_debug') && self::$container->getParameter('twig_debug')) {
            $twig->enableDebug();
        }
        self::$container->set('twig', $twig);
    }

    /**
     * @return DI|ContainerInterface
     */
    public static function getContainer()
    {
        if (self::$container === null) {
            new self();
        }
        return self::$container;
    }

    /**
     * @param RouteCollection $routes
     */
    public static function setRoutes(RouteCollection $routes)
    {
        self::$routes = $routes;
    }
}