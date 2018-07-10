<?php
declare(strict_types = 1);
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
    public function getConnection(): \PDO;

    /**
     * @param bool|\PDO $connection
     */
    public function setConnection($connection);

    /**
     * @return string
     */
    public function getTableName(): string;

    /**
     * @param string $tableName
     */
    public function setTableName(string $tableName);
}
