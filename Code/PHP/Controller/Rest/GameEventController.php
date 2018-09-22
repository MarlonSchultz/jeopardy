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

    public function setQuestionOpenAction(int $questionId): JsonResponse
    {
        $returnVal = $this->gameEventsModel->insertNewOpenAnswer($questionId);
        if ($returnVal) {
            return new JsonResponse('Inserted new row');
        }

        return new JsonResponse('Could not insert new row');
    }

    public function getAllGameEventsAction(): JsonResponse
    {
        return new JsonResponse($this->gameEventsModel->getAllGameEvents());
    }

    public function setQuestionClosed(int $eventId): JsonResponse
    {
        $returnVal = $this->gameEventsModel->closeAnswer($eventId);

        if ($returnVal) {
            return new JsonResponse('Closed');
        }

        return new JsonResponse('Cloud not close');
    }


    public function setOpenQuestionToWrongAction(): JsonResponse
    {
        if ($this->gameEventsModel->setOpenQuestionToWrong() === 1) {
            $this->reinsertQuestion();
            return new JsonResponse('Marked as wrongly answered');
        }

        return new JsonResponse('Could not find open question');
    }

    public function setOpenQuestionToCorrectAction(): JsonResponse
    {
        if ($this->gameEventsModel->setOpenQuestionToCorrect() === 1) {
            return new JsonResponse('Marked as correct answered');
        }

        return new JsonResponse('Could not find open question');
    }

    public function closeOpenQuestionsAction(): JsonResponse
    {
        if ($this->gameEventsModel->closeQuestions()) {
            return new JsonResponse('All open questions are closed');
        }

        return new JsonResponse('Some Database Sanfu happened');
    }

    private function reinsertQuestion(): void
    {
        $this->gameEventsModel->cloneLastAnswer();
    }
}
