<?php
/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 07.09.18
 * Time: 16:02
 */

namespace mgbs\Model;

class GameEventsModel extends BaseModel
{
    public function insertBuzz(int $buzzer): bool
    {
        $sql = 'UPDATE ' . $this->getTableName() . ' SET buzzer_id = :buzzer_id WHERE buzzer_id is null';

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':buzzer_id', $buzzer, \PDO::PARAM_INT);
        return $statement->execute();
    }

    public function getAnswerById(int $questionId)
    {
        $sql = 'SELECT question_id,
                        buzzer_id,
                        correct_or_false,
                        game_event_id,
                        question_closed from ' . $this->getTableName() . ' WHERE question_id = :questionId';

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':questionId', $questionId, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_OBJ);
    }

    public function isAnswerOpen(int $questionId): bool
    {
        $sql = 'SELECT count(question_id) as countId from ' . $this->getTableName()
            . ' WHERE question_id = :questionId and question_is_open';

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':questionId', $questionId, \PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_OBJ);
        return ((int)$result->countId) === 1;
    }

    public function insertNewOpenAnswer(int $answerId): bool
    {
        $sql = 'insert into ' . $this->getTableName()
            . ' (question_id) VALUES (:answerId)';
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':answerId', $answerId, \PDO::PARAM_INT);
        return $statement->execute();
    }

    public function getAllGameEvents()
    {
        $sql = 'SELECT question_id,
                        buzzer_id,
                        correct_or_false,
                        game_event_id,
                        question_closed
                        from ' . $this->getTableName();

        $statement = $this->connection->query($sql);
        return $statement->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getLastAnswerWithOutBuzzer()
    {
        $sql = 'SELECT question_id,
                        buzzer_id,
                        correct_or_false,
                        game_event_id
                        from ' . $this->getTableName() . '
        WHERE buzzer_id is null and question_closed = 0 
        order by game_event_id desc limit 1';

        $statement = $this->connection->query($sql);
        return $statement->fetchAll(\PDO::FETCH_OBJ);
    }

    public function cloneLastAnswer() : bool
    {
        $sql = 'insert into ' . $this->getTableName() . ' (question_id) 
        VALUES ((SELECT question_id from ' . $this->getTableName() . '
                order by game_event_id desc limit 1))';

        $statement = $this->connection->prepare($sql);
        return $statement->execute();
    }

    public function closeQuestions()
    {
        $sql = 'UPDATE ' . $this->getTableName() . ' SET question_closed = 1';

        return $this->connection->query($sql);
    }

    public function setOpenQuestionToWrong(): int
    {
        $sql = 'UPDATE ' . $this->getTableName() . ' SET correct_or_false = 0 WHERE 
        correct_or_false is null 
        AND game_event_id=(SELECT MAX(game_event_id) from ' . $this->getTableName() . ')
        AND buzzer_id is not null';

        $statement = $this->connection->query($sql);
        return $statement->rowCount();
    }

    public function setOpenQuestionToCorrect(): int
    {
        $sql = 'UPDATE ' . $this->getTableName() . ' SET correct_or_false = 1 WHERE 
        correct_or_false is null
        AND game_event_id=(SELECT MAX(game_event_id) from ' . $this->getTableName() . ')
        AND buzzer_id is not null';

        $statement = $this->connection->query($sql);
        return $statement->rowCount();
    }
}
