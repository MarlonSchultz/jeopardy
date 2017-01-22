<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 22.01.17
 * Time: 13:57
 */

namespace mgbs\Controller;


use mgbs\Model\QuestionsModel;
use mgbs\ValueObject\JeopardyCollection;
use mgbs\ValueObject\JeopardyItem;
use mgbs\ValueObject\JeopardyRowCollection;

class JeopardyCollectionFactory
{
    /**
     * @var JeopardyCollection
     */
    private $jeopardyCollection;
    /**
     * @var JeopardyRowCollection
     */
    private $jeopardyRowCollection;
    /**
     * @var QuestionsModel
     */
    private $model;

    /**
     * JeopardyCollectionFactory constructor.
     * @param JeopardyCollection $jeopardyCollection
     * @param JeopardyRowCollection $jeopardyRowCollection
     */
    public function __construct(
        JeopardyCollection $jeopardyCollection,
        JeopardyRowCollection $jeopardyRowCollection
    ) {
        $this->jeopardyCollection = $jeopardyCollection;
        $this->jeopardyRowCollection = $jeopardyRowCollection;
    }


    public function buildCollection(int $points)
    {
        // todo: finish this shit next commit @myself (marlon)
        foreach ($this->model->getQuestionsByPoints($points) as $singleQuestion)
        {
            $this->jeopardyRowCollection->offsetSet(null, new JeopardyItem(, 10));
        }
        return $this->jeopardyCollection->addElement($points, $this->jeopardyRowCollection);
    }

    /**
     * @param QuestionsModel $model
     */
    public function setModel(QuestionsModel $model)
    {
        $this->model = $model;
    }

    /**
     * @return ModelInterface
     */
    public function getModel(): QuestionsModel
    {
        return $this->model;
    }
}