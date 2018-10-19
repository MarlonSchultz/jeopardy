<?php
declare(strict_types=1);

namespace mgbs\Controller;

use mgbs\Library\DITrait;
use mgbs\Model\GameEventsModel;
use mgbs\Model\ModelInterface;
use mgbs\Model\QuestionsModel;
use mgbs\ValueObject\JeopardyCollection;
use mgbs\ValueObject\JeopardyCollectionFactory;
use mgbs\ValueObject\JeopardyRowCollection;
use mgbs\DTO\JeopardyItem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RouteCollection;

/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 16.01.17
 * Time: 19:37
 */
class IndexController
{
    use DITrait;

    /**
     * @var QuestionsModel
     */
    private $questionsModel;

    /**
     * @return Response
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     * @throws \InvalidArgumentException
     */
    public function indexAction(): Response
    {
        $jeopardyCollectionFactory = new JeopardyCollectionFactory(new JeopardyCollection());
        $jeopardyCollectionFactory->setModel($this->getService('questionmodel'));

        $jeopardyCollectionFactory->addRowCollection(new JeopardyRowCollection(), 10);
        $jeopardyCollectionFactory->addRowCollection(new JeopardyRowCollection(), 20);
        $jeopardyCollectionFactory->addRowCollection(new JeopardyRowCollection(), 30);
        $jeopardyCollectionFactory->addRowCollection(new JeopardyRowCollection(), 40);
        $jeopardyCollectionFactory->addRowCollection(new JeopardyRowCollection(), 50);

        $jeopardyCollection = $jeopardyCollectionFactory->getCollection();

        /** @var \Twig_Environment $twig */
        $twig = $this->getService('twig');
        return new Response($twig->render('jeopardy.html.twig', ['jeopardy' => $jeopardyCollection]));
    }

    /**
     * @return Response
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     * @throws \InvalidArgumentException
     */
    public function moderatorAction(): Response
    {
        $deleted = false;

        if ($this->getService('request')->get('resetGameEvents') !== null) {
            $gameEventModel = $this->getGameEventsModel();
            $gameEventModel->resetAllGameEvents();
            $deleted = true;
        }


        $jeopardyCollectionFactory = new JeopardyCollectionFactory(new JeopardyCollection());
        $jeopardyCollectionFactory->setModel($this->getService('questionmodel'));

        $jeopardyCollectionFactory->addRowCollection(new JeopardyRowCollection(), 10);
        $jeopardyCollectionFactory->addRowCollection(new JeopardyRowCollection(), 20);
        $jeopardyCollectionFactory->addRowCollection(new JeopardyRowCollection(), 30);
        $jeopardyCollectionFactory->addRowCollection(new JeopardyRowCollection(), 40);
        $jeopardyCollectionFactory->addRowCollection(new JeopardyRowCollection(), 50);

        $jeopardyCollection = $jeopardyCollectionFactory->getCollection();

        /** @var \Twig_Environment $twig */
        $twig = $this->getService('twig');
        return new Response($twig->render(
            'moderator.html.twig',
            ['jeopardy' => $jeopardyCollection, 'deleted' => $deleted]
        ));
    }

    /**
     * @return Response
     *
     * @throws \Exception
     */
    public function adminAction(): Response
    {
        if ($this->getParameter('db_type') !== 'sqlite3') {
            throw new \Exception('Admin only available for sqlite database');
        }
        if ($this->getService('request')->get('save')) {
            /** @var Request $request */
            $request = $this->getService('request');
            $jeopardyItem = new JeopardyItem(
                $request->get('id'),
                $request->get('category'),
                $request->get('points'),
                $request->get('answer'),
                $request->get('question')
            );
            $this->getService('questionmodel')->updateEntry($jeopardyItem);
        }
        $entries = $this->getService('questionmodel')->getAllQuestions();
        return new Response(
            $this->getService('twig')->render('admin.html.twig', array('entries' => $entries))
        );
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
    public function setQuestionsModel(ModelInterface $questionsModel): void
    {
        $this->questionsModel = $questionsModel;
    }

    public function getGameEventsModel(): GameEventsModel
    {
        return $this->getService('GameEventsModel');
    }
}
