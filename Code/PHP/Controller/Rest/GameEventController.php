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

    public function getAllPlayerEventsAction(): JsonResponse
    {
        return new JsonResponse($this->gameEventsModel->getAllGameEvents());
    }

    public function setQuestionClosed(int $eventId)
    {
        $returnVal = $this->gameEventsModel->closeAnswer($eventId);

        if ($returnVal) {
            return new JsonResponse('Closed');
        }

        return new JsonResponse('Cloud not close');
    }
}
