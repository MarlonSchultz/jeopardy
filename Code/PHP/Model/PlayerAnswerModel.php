<?php
/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 07.09.18
 * Time: 16:02
 */

namespace mgbs\Model;

class PlayerAnswerModel extends BaseModel
{
    public function insertBuzz(int $buzzer, int $questionId): bool
    {
        $sql = 'insert into ' . $this->getTableName()
            . ' (question_id, buzzer_id, correct_or_false) VALUES (1,:buzzer,0)';
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':buzzer', $buzzer, \PDO::PARAM_INT);
        return $statement->execute();
    }

    public function getAnswerById(int $questionId)
    {
        $sql = 'SELECT question_id,
                        buzzer_id,
                        correct_or_false,
                        player_answer_id,
                        question_is_open from ' . $this->getTableName() . ' WHERE question_id = :questionId';

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':questionId', $questionId, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_OBJ);
    }

    public function isAnswerOpen(int $questionId): bool
    {
        $sql = 'SELECT count(question_id) as countId from ' . $this->getTableName() . ' WHERE question_id = :questionId and question_is_open';

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':questionId', $questionId, \PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_OBJ);
        return ((int)$result->countId) === 1;
    }

    public function setAnswerOpenClose(int $playerAnswerId, bool $open = true): bool
    {
        $openInt = $open ? 1 : 0;
        $sql = 'UPDATE ' . $this->getTableName() . ' SET question_is_open = :open WHERE player_answer_id = :answerId';

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':answerId', $playerAnswerId, \PDO::PARAM_INT);
        $statement->bindValue(':open', $openInt, \PDO::PARAM_INT);
        return $statement->execute();
    }
}

