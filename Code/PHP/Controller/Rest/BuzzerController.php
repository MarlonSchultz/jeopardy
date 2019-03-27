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
        $file = __DIR__ . '/../../Var/Data/activeBuzzer';
        if (file_get_contents(__DIR__ . '/../../Var/Data/openQuestion') === 'true') {
            file_put_contents($file, $buzzer);
            file_put_contents(__DIR__ . '/../../Var/Data/openQuestion', 'false');
            return new JsonResponse('buzzered');
        }
        return new JsonResponse('No open answer found');
    }

    public function hasLastQuestionBeenBuzzered()
    {
        $file = __DIR__ . '/../../Var/Data/activeBuzzer';
        if (file_exists($file)) {
            $fileContent = (int)file_get_contents($file);
            if ($fileContent !== 0) {
                file_put_contents($file, 0);
                return new JsonResponse(json_encode(['buzzer_id' => $fileContent]));
            }
        }
        return new JsonResponse(json_encode(['buzzer_id' => 0]));
    }
}
