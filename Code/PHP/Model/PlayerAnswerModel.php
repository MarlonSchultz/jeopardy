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
    public function insertBuzz(int $buzzer): bool
    {
        $sql = 'insert into ' . $this->getTableName()
            . ' (question_id, buzzer_id, correct_or_false) VALUES (1,:buzzer,0)';
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':buzzer', $buzzer, \PDO::PARAM_INT);
        return $statement->execute();
    }
}
