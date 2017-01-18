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

        public function __construct()
        {

    }
    public function indexAction()
    {
        $sqlite = DI::getContainer()->get('sqlite');
        /** @var \Twig_Environment $twig */
        $twig = DI::getContainer()->get('twig');
        return new Response($twig->render('jeopardy.html.twig'));
    }
}