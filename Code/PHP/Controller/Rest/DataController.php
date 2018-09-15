<?php
declare(strict_types=1);

namespace mgbs\Controller\Rest;


use mgbs\Library\DITrait;
use mgbs\Model\QuestionsModel;
use Symfony\Component\HttpFoundation\JsonResponse;

class DataController
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

    public function getAnswersAction(): JsonResponse
    {
        if (!\is_array($data = $this->questionModel->getAllQuestions())) {
            throw new \DatabaseException('Seems DB is empty');
        }
        return new JsonResponse($data);
    }


}