<?php
declare(strict_types = 1);

namespace mgbs\Model;

/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 18.01.17
 * Time: 21:41
 */
final class Questions extends ModelAbstract
{
    const tableName = 'Questions';

    public function getAllQuestions()
    {
        $this->getConnection()->query('select * from '.self::tableName);
    }


}