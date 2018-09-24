<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 29.01.17
 * Time: 14:39
 */

namespace Tests\Library\Database;

use mgbs\Library\Database\DatabaseFactory;
use PHPUnit\Framework\TestCase;

class DatabaseFactoryTest extends TestCase
{
    public function testIfSQLite3ReturnsPDOObject()
    {
        $database = new DatabaseFactory('sqlite3', __DIR__ . '/Fixtures/example.db', __DIR__ . '/Fixtures/example.db');
        self::assertTrue(is_a($database->getConnection(), \PDO::class));
    }

    public function testIfFlatfileReturnsPDOOBject()
    {
        $database = new DatabaseFactory('flatfile', __DIR__ . '/Fixtures/database.json', __DIR__ . '/Fixtures/example.db');
        self::assertTrue(is_a($database->getConnection(), \PDO::class));
    }

    public function testIfFactoryThrowsExceptionIfDBTypeIsUnknown()
    {
        $database = new DatabaseFactory('strangeDbType', __DIR__ . '/Fixtures/example.db',__DIR__ . '/Fixtures/example.db');
        $this->expectException(\InvalidArgumentException::class);
        $database->getConnection();
    }

    public function testIfFactoryThrowsExceptionIfDatabaseFileIsMissing()
    {
        $database = new DatabaseFactory('strangeDbType', __DIR__ . 'dataBaseThatDoesNotExist',__DIR__ . '/Fixtures/example.db');
        $this->expectException(\InvalidArgumentException::class);
        $database->getConnection();
    }


}
