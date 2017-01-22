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
class BaseModel implements ModelInterface
{
    /** @var \PDO  */
    protected $connection;

    protected $tableName = '';

    public function __construct(Database $database)
    {
        $this->connection = $database->getConnection();
    }

    /**
     * @return bool|\PDO
     */
    public function getConnection(): \PDO
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
     * @throws \InvalidArgumentException
     */
    public function getTableName(): string
    {
        if ('' === $this->tableName) {
            throw new \InvalidArgumentException('TableName setter must be called first');
        }
        return $this->tableName;
    }

    /**
     * @param string $tableName
     */
    public function setTableName(string $tableName)
    {
        $this->tableName = $tableName;
    }


}