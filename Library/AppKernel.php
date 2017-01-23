<?php
declare(strict_types = 1);
namespace mgbs\Library;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 16.01.17
 * Time: 19:26
 */
class AppKernel
{
    /**
     * @var HttpKernel|HttpKernelInterface
     */
    private $kernel;

    /**
     * AppKernel constructor.
     * @param RouteCollection $routeCollection
     * @throws \InvalidArgumentException
     */
    public function __construct(RouteCollection $routeCollection)
    {
        $this->kernel = $this->initKernel($routeCollection);
    }

    /**
     * @param RouteCollection $routeCollection
     * @return HttpKernelInterface|HttpKernel
     * @throws \InvalidArgumentException
     */
    private function initKernel(RouteCollection $routeCollection)
    {
        $matcher = new UrlMatcher($routeCollection, new RequestContext());

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new RouterListener($matcher, new RequestStack()));

        $controllerResolver = new ControllerResolver();
        $argumentResolver = new ArgumentResolver();

        return new HttpKernel($dispatcher, $controllerResolver, new RequestStack(), $argumentResolver);
    }

    /**
     * @return HttpKernel|HttpKernelInterface
     */
    public function getKernel()
    {
        return $this->kernel;
    }
}
