<?php
declare(strict_types = 1);
namespace mgbs\Controller;

use mgbs\Model\QuestionsModel;
use mgbs\ValueObject\JeopardyCollection;
use mgbs\ValueObject\JeopardyCollectionFactory;
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
     * @return Response
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     * @throws \InvalidArgumentException
     */
    public function indexAction()
    {

        $jeopardyCollectionFactory = new JeopardyCollectionFactory(new JeopardyCollection());
        $jeopardyCollectionFactory->setModel(DI::getContainer()->get('questionmodel'));

        $jeopardyCollectionFactory->addRowCollection(new JeopardyRowCollection, 10);
        $jeopardyCollectionFactory->addRowCollection(new JeopardyRowCollection, 20);
        $jeopardyCollectionFactory->addRowCollection(new JeopardyRowCollection, 30);
        $jeopardyCollectionFactory->addRowCollection(new JeopardyRowCollection, 40);
        $jeopardyCollectionFactory->addRowCollection(new JeopardyRowCollection, 50);

        $jeopardyCollection = $jeopardyCollectionFactory->getCollection();

        /** @var \Twig_Environment $twig */
        $twig = DI::getContainer()->get('twig');
        return new Response($twig->render('jeopardy.html.twig', ['jeopardy' => $jeopardyCollection]));
    }

    /**
     * @return Response
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     * @throws \InvalidArgumentException
     */
    public function moderatorAction()
    {

        $jeopardyCollectionFactory = new JeopardyCollectionFactory(new JeopardyCollection());
        $jeopardyCollectionFactory->setModel(DI::getContainer()->get('questionmodel'));

        $jeopardyCollectionFactory->addRowCollection(new JeopardyRowCollection, 10);
        $jeopardyCollectionFactory->addRowCollection(new JeopardyRowCollection, 20);
        $jeopardyCollectionFactory->addRowCollection(new JeopardyRowCollection, 30);
        $jeopardyCollectionFactory->addRowCollection(new JeopardyRowCollection, 40);
        $jeopardyCollectionFactory->addRowCollection(new JeopardyRowCollection, 50);

        $jeopardyCollection = $jeopardyCollectionFactory->getCollection();

        /** @var \Twig_Environment $twig */
        $twig = DI::getContainer()->get('twig');
        return new Response($twig->render('moderator.html.twig', ['jeopardy' => $jeopardyCollection]));
    }
}
