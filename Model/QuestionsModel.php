<?php
declare(strict_types = 1);

namespace mgbs\Model;

/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 18.01.17
 * Time: 21:41
 */
final class QuestionsModel extends BaseModel
{
    public function getAllQuestions()
    {
        return $this->getConnection()->query('SELECT * FROM ' . $this->getTableName());
    }


}