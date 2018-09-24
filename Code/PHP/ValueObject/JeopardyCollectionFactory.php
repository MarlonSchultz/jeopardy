<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 22.01.17
 * Time: 13:57
 */

namespace mgbs\ValueObject;

use mgbs\Model\QuestionsModel;

class JeopardyCollectionFactory
{
    /**
     * @var JeopardyCollection
     */
    private $jeopardyCollection;

    /**
     * @var QuestionsModel
     */
    private $model;

    /**
     * JeopardyCollectionFactory constructor.
     * @param JeopardyCollection $jeopardyCollection
     */
    public function __construct(
        JeopardyCollection $jeopardyCollection
    ) {
        $this->jeopardyCollection = $jeopardyCollection;
    }


    public function addRowCollection(
        JeopardyRowCollection $jeopardyRowCollection,
        int $points
    ): JeopardyCollectionFactory {

        foreach ($this->model->getQuestionsByPoints($points) as $singleQuestion) {
            $jeopardyRowCollection->offsetSet(
                null,
                new JeopardyItem(
                    $singleQuestion->question,
                    $singleQuestion->answer,
                    $singleQuestion->category,
                    $singleQuestion->points,
                    $singleQuestion->id
                )
            );
        }
        $this->jeopardyCollection->addElement($points, $jeopardyRowCollection);
        return $this;
    }

    /**
     * @param QuestionsModel $model
     */
    public function setModel(QuestionsModel $model)
    {
        $this->model = $model;
    }

    /**
     * @return QuestionsModel
     */
    public function getModel(): QuestionsModel
    {
        return $this->model;
    }

    /**
     * @return JeopardyCollection
     */
    public function getCollection(): JeopardyCollection
    {
        return $this->jeopardyCollection;
    }
}
