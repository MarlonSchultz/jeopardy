<?php
declare(strict_types = 1);
namespace mgbs\Controller;

use mgbs\Model\ModelInterface;
use mgbs\Model\Questions;
use mgbs\ValueObject\JeopardyCollection;
use mgbs\ValueObject\JeopardyItem;
use mgbs\ValueObject\JeopardyRowCollection;
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
        /**
         * @todo must be filled with real database values
         */
        $jeopardyCollection = new JeopardyCollection();
        $jeopardyRowCollection10 = new JeopardyRowCollection();
        $jeopardyRowCollection10->offsetSet(null, new JeopardyItem('The meaning of life?', '42', 'basic', 10));
        $jeopardyRowCollection10->offsetSet(null, new JeopardyItem('Why not?', 'Because of', 'misc', 10));
        $jeopardyCollection->addElement(10, $jeopardyRowCollection10);
        $jeopardyRowCollection20 = new JeopardyRowCollection();
        $jeopardyRowCollection20->offsetSet(null, new JeopardyItem('The question?', 'The answer', 'basic', 20));
        $jeopardyRowCollection20->offsetSet(null, new JeopardyItem('Who am I?', 'Not Root', 'misc', 20));
        $jeopardyCollection->addElement(20, $jeopardyRowCollection20);
        /**
         * END
         */

        /** @var \Twig_Environment $twig */
        $twig = DI::getContainer()->get('twig');
        return new Response($twig->render('jeopardy.html.twig', array('jeopardy' => $jeopardyCollection)));
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