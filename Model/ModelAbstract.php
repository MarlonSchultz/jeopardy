<?php
declare(strict_types = 1);

namespace mgbs\Model;

use mgbs\Library\Database;

/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 18.01.17
 * Time: 21:47
 */
class ModelAbstract implements ModelInterface
{
    protected $connection;

    public function __construct(Database $database)
    {
        $this->connection = $database->getConnection();
    }

    /**
     * @return bool|\PDO
     */
    public function getConnection() : \PDO
    {
        return $this->connection;
    }

    /**
     * @param bool|\PDO $connection
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return string
     */
    public function getTablename() : string
    {
        return $this->tablename;
    }

    /**
     * @param mixed $tablename
     */
    public function setTablename($tablename)
    {
        $this->tablename = $tablename;
    }


}