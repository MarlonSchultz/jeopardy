<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 30.01.17
 * Time: 21:27
 */

namespace mgbs\Tests\Library\Database;

use mgbs\Library\Database\Sqlite3Adapter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class Sqlite3AdapterTest extends TestCase
{
    public function testIfFileNotFoundExceptionIsThrownIfFileIsNotFound()
    {
        $this->expectException(FileNotFoundException::class);
        new Sqlite3Adapter('FileThatDoesntExist');
    }
}
