<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 30.01.17
 * Time: 21:27
 */

namespace mgbs\Tests\Library\Database;

use mgbs\Library\Database\JsonAdapter;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class JsonAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testIfFileNotFoundExceptionIsThrownIfFileIsNotFound()
    {
        $this->expectException(FileNotFoundException::class);
        new JsonAdapter('FileThatDoesntExist');
    }
}
