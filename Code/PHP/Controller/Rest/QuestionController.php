<?php
declare(strict_types=1);

namespace mgbs\Controller\Rest;

use mgbs\Exceptions\DatabaseException;
use mgbs\Library\DITrait;
use mgbs\Model\QuestionsModel;
use Symfony\Component\HttpFoundation\JsonResponse;

class QuestionController
{
    use DITrait;

    /**
     * @var QuestionsModel
     */
    private $questionModel;


    public function __construct()
    {
        $this->questionModel = $this->getService('questionmodel');
    }

    public function getAllAnswersAction(): JsonResponse
    {
        if (!\is_array($data = $this->questionModel->getAllQuestions())) {
            throw new DatabaseException('Seems DB is empty');
        }
        return new JsonResponse($data, 200, ['Access-Control-Allow-Origin' => '*']);
    }
}
