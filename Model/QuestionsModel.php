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
    /**
     * @throws \InvalidArgumentException
     */
    public function getAllQuestions()
    {
        $sql = 'SELECT 
                  id,
                  category,
                  points,
                  answer,
                  question
                FROM ' . $this->getTableName();
        return $this->connection->query($sql)->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * @param string $category
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getQuestionsByCategory(string $category)
    {
        $sql = 'SELECT 
                  id,
                  category,
                  points,
                  answer,
                  question
                FROM ' . $this->getTableName() . '
                WHERE category = :categoryName';
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':categoryName', $category, \PDO::PARAM_STR);
        return $statement->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * @param int $points
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getQuestionsByPoints(int $points)
    {
        $sql = 'SELECT 
                  id,
                  category,
                  points,
                  answer,
                  question
                FROM ' . $this->getTableName() . '
                WHERE points = :pointValue
                ORDER by category';
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':pointValue', $points, \PDO::PARAM_INT);
        if ($statement->execute()) {
            return $statement->fetchAll(\PDO::FETCH_OBJ);
        }
    }
}
