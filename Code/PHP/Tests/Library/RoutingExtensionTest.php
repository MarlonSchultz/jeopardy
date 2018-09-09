<?php
/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 09.09.18
 * Time: 18:30
 */

namespace mgbs\PHP\Library;

use mgbs\Library\Routes;
use mgbs\Library\RoutingExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;

class RoutingExtensionTest extends TestCase
{
    /** @var RoutingExtension */
    private $routingExtension;

    public function setUp()
    {
        $this->routingExtension = new RoutingExtension($this->getMockBuilder(UrlGenerator::class)->disableOriginalConstructor()->setMockClassName('UrlGenerator')->getMock());
    }

    /**
     * @test
     */
    public function setUpHasDoneItsWork()
    {
        $this->assertTrue(is_a($this->routingExtension, RoutingExtension::class));
    }

    public function testGetFunctions()
    {
        foreach ($this->routingExtension->getFunctions() as $singleFunction) {
            $this->assertTrue(is_a($singleFunction, \Twig_SimpleFunction::class));
        }
    }

}
