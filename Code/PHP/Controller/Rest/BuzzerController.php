<?php
declare(strict_types=1);

namespace mgbs\Controller\Rest;

use mgbs\Library\DITrait;
use mgbs\Model\GameEventsModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 16.01.17
 * Time: 19:37
 */
class BuzzerController
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

    /**
     * @return Response
     */
    public function buzzerAction($buzzer): Response
    {
        if (sizeof($lastAnswer = $this->gameEventsModel->getLastAnswerWithOutBuzzer()) === 1) {
            $this->gameEventsModel->insertBuzz((int)$buzzer);
            return new JsonResponse('buzzed');
        }
        return new JsonResponse('No open answer found');
    }

    public function hasLastQuestionBeenBuzzered()
    {
        return new JsonResponse($this->gameEventsModel->getLastOpenAnswerWithBuzzer());
    }
}
