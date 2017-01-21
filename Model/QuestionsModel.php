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
        $sql = 'SELECT * FROM :tableName';
        $statement = $this->getConnection()->prepare($sql);
        $statement->bindValue(':tableName', $this->getTableName());
        if ($statement->execute()){
            return $statement->fetchAll();
        }
    }


}