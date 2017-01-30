<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 29.01.17
 * Time: 17:53
 */

namespace mgbs\Library\Database;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class Sqlite3Adapter implements DatabaseAdapterInterface
{
    /**
     * @var String $filename
     */
    private $filename;

    private $pdo;

    public function __construct(String $filename)
    {
        $this->filename = $filename;
        $this->initPdoInstance();
    }

    public function initPdoInstance()
    {
        if (!file_exists($this->filename)) {
            throw new FileNotFoundException('Databasefile' . $this->filename . 'not found. Current BaseDir:' . __DIR__);
        }

        try {
            $this->pdo = new \PDO('sqlite:' . $this->filename);
        } catch (\Exception $e) {
            throw new \RuntimeException('Cannot connect to database: ' . $e->getMessage());
        }
    }

    public function getPdoInstance(): \PDO
    {
        return $this->pdo;
    }
}
