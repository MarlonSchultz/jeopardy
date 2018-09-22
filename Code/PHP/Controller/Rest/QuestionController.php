<?php
declare(strict_types=1);

namespace mgbs\Controller\Rest;

use mgbs\Library\DITrait;
use mgbs\Model\PlayerAnswerModel;
use mgbs\Model\QuestionsModel;
use Symfony\Component\HttpFoundation\JsonResponse;

class QuestionController
{
    use DITrait;

    /**
     * @var QuestionsModel
     */
    private $questionModel;
    /**
     * @var PlayerAnswerModel
     */
    private $playerAnswerModel;

    public function __construct()
    {
        $this->questionModel = $this->getService('questionmodel');
        $this->playerAnswerModel = $this->getService('playeranswermodel');
    }

    public function getAllAnswersAction(): JsonResponse
    {
        if (!\is_array($data = $this->questionModel->getAllQuestions())) {
            throw new \DatabaseException('Seems DB is empty');
        }
        return new JsonResponse($data);
    }

    public function setQuestionOpenAction(int $questionId): JsonResponse
    {
        $isAnswerOpen = $this->playerAnswerModel->isAnswerOpen($questionId);
        if (!$isAnswerOpen) {
            $this->playerAnswerModel->setAnswerOpenClose(1, true);
        }
        return new JsonResponse($isAnswerOpen);
    }

    public function getAllPlayerEventsAction(): JsonResponse
    {
        return new JsonResponse($this->playerAnswerModel->getAllPlayerEvents());
    }
}
