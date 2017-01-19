<?php
/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 19.01.17
 * Time: 14:37
 */

namespace mgbs\Model;


interface ModelInterface
{
    /**
     * @return bool|\PDO
     */
    public function getConnection() : \PDO;

    /**
     * @param bool|\PDO $connection
     */
    public function setConnection($connection);

    /**
     * @return mixed
     */
    public function getTablename() : string;

    /**
     * @param mixed $tablename
     */
    public function setTablename($tablename);
}