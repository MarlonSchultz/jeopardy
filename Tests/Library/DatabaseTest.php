<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 29.01.17
 * Time: 14:39
 */

namespace Tests\Library;

use mgbs\Library\Database;

class DatabaseTest extends \PHPUnit_Framework_TestCase
{
    public function testIfSQLite3ReturnsPDOObject()
    {
        $database = new Database('sqlite3', __DIR__ . '/Fixtures/example.db');
        self::assertTrue(is_a($database->getConnection(), \PDO::class));
    }

    public function testIfFactoryThrowsExceptionIfDBTypeIsUnknown()
    {
        $database = new Database('strangeDbType', __DIR__ . '/Fixtures/example.db');
        $this->expectException(\InvalidArgumentException::class);
        $database->getConnection();
    }

    public function testIfFactoryThrowsExceptionIfDatabaseFileIsMissing()
    {
        $database = new Database('strangeDbType', __DIR__ . 'dataBaseThatDoesNotExist');
        $this->expectException(\InvalidArgumentException::class);
        $database->getConnection();
    }


}
