<?php
/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 22.09.18
 * Time: 17:58
 */

namespace mgbs\Controller\Rest;

use mgbs\Library\DITrait;
use mgbs\Model\GameEventsModel;
use Symfony\Component\HttpFoundation\JsonResponse;

class GameEventController
{
    use DITrait;
    /**
     * @var GameEventsModel
     */
    private $gameEventsModel;

    public function __construct()
    {
        $this->gameEventsModel = $this->getService('gameeventsmodel');
    }

    public function setQuestionOpenAction(): JsonResponse
    {
        file_put_contents(__DIR__ . '/../../Var/Data/openQuestion', 'true');
        return new JsonResponse('Question opened for buzzing');
    }

    public function getAllGameEventsAction(): JsonResponse
    {
        return new JsonResponse($this->gameEventsModel->getAllGameEvents());
    }

    public function setQuestionClosed(): JsonResponse
    {
        file_put_contents(__DIR__ . '/../../Var/Data/openQuestion', 'false');
        return new JsonResponse('Question closed for buzzing');
    }

    public function setOpenQuestionToWrongAction(): JsonResponse
    {
        if ($this->gameEventsModel->setOpenQuestionToWrong() === 1) {
            $this->reinsertQuestion();
            return new JsonResponse('Marked as wrongly answered', 200);
        }

        return new JsonResponse('Could not find open question', 400);
    }

    public function setOpenQuestionToCorrectAction(): JsonResponse
    {
        if ($this->gameEventsModel->setOpenQuestionToCorrect() === 1) {
            return new JsonResponse('Marked as correct answered', 200);
        }

        return new JsonResponse('Could not find open question', 400);
    }

    public function closeOpenQuestionsAction(): JsonResponse
    {
        file_put_contents(__DIR__ . '/../../Var/Data/openQuestion', 'false');
        return new JsonResponse('Question closed for buzzing');
    }

    private function reinsertQuestion(): void
    {
        $this->gameEventsModel->cloneLastAnswer();
    }
}
