<?php
declare(strict_types = 1);
namespace mgbs\Controller;

use mgbs\Model\ModelAbstract;
use Symfony\Component\HttpFoundation\Response;
use mgbs\Library\DI;

/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 16.01.17
 * Time: 19:37
 */
class IndexController
{
    /**
     * @var ModelAbstract
     */
    private $questionsModel;

    public function __construct($questionsModel)
    {
        $this->questionsModel = $questionsModel;
    }

    public function indexAction()
    {
        $sqlite = DI::getContainer()->get('sqlite');
        /** @var \Twig_Environment $twig */
        $twig = DI::getContainer()->get('twig');
        return new Response($twig->render('jeopardy.html.twig'));
    }
}