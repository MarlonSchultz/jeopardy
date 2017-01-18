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
class ModelAbstract
{
    protected $connection;

    protected $tablename;

    public function __construct(Database $database)
    {
        $this->connection = $database->getConnection();
    }

    /**
     * @return bool|\PDO
     */
    public function getConnection()
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
     * @return mixed
     */
    public function getTablename()
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