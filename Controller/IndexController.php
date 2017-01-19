<?php
declare(strict_types = 1);
namespace mgbs\Controller;

use mgbs\Model\ModelInterface;
use mgbs\Model\Questions;
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
     * @var Questions
     */
    private $questionsModel;

    public function indexAction()
    {
        $this->questionsModel = DI::getContainer()->get('questionmodel');

        // encapsulated call for data from the model
        $this->questionsModel->getAllQuestions();

        // or less elegant "quick and dirty" write a query on the fly be getting the connection itself
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