<?php
declare(strict_types = 1);
namespace mgbs\Controller;

use mgbs\Library\DI;
use Symfony\Component\HttpFoundation\Response;

/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 16.01.17
 * Time: 19:37
 */
    class IndexController
{
    public function indexAction()
    {
        $sqlite = DI::getContainer()->get('sqlite');
        return new Response('basics are set');
    }
}