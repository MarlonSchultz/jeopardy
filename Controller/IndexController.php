<?php
declare(strict_types = 1);
namespace mgbs\Controller;

use mgbs\Model\ModelAbstract;
use mgbs\Model\ModelInterface;
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
     * @var ModelInterface
     */
    private $questionsModel;

    public function indexAction()
    {
        $this->questionsModel = DI::getContainer()->get('questionmodel');

        $this->questionsModel->getConnection()->query('select whatever');

        /** @var \Twig_Environment $twig */
        $twig = DI::getContainer()->get('twig');
        return new Response($twig->render('jeopardy.html.twig'));
    }

    /**
     * @return ModelInterface
     */
    public function getQuestionsModel(): ModelInterface
    {
        return $this->questionsModel;
    }

    /**
     * @param ModelInterface $questionsModel
     */
    public function setQuestionsModel(ModelInterface $questionsModel)
    {
        $this->questionsModel = $questionsModel;
    }
}