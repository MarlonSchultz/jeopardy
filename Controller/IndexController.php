<?php
declare(strict_types = 1);
namespace mgbs\Controller;

use mgbs\Model\ModelInterface;
use mgbs\Model\Questions;
use mgbs\Model\QuestionsModel;
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
     * @var QuestionsModel
     */
    private $questionsModel;

    public function indexAction()
    {
        $this->questionsModel = DI::getContainer()->get('questionmodel');

        $jeopardyCollection = new JeopardyCollectionFactory(new JeopardyCollection(), new JeopardyRowCollection(), $this->questionsModel);
//        $jeopardyCollection = new JeopardyCollection();
//        $jeopardyRowCollection10 = new JeopardyRowCollection();


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