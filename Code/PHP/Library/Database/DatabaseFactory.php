<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 18.01.17
 * Time: 20:31
 */

namespace mgbs\Library\Database;

class DatabaseFactory
{
    /**
     * @var string $databaseType
     */
    private $databaseType;

    /**
     * @var string $databaseFile Should contain filename for database
     */
    private $databaseFile;
    /**
     * @var string
     */
    private $sqliteFileToDumpFlatFileIn;

    /**
     * @param string $databaseType
     * @param string|null $databaseFile
     * @param string $sqliteFileToDumpFlatFileIn
     */
    public function __construct(
        string $databaseType,
        string $databaseFile = null,
        string $sqliteFileToDumpFlatFileIn
    ) {

        $this->databaseType = $databaseType;
        $this->databaseFile = $databaseFile;
        $this->sqliteFileToDumpFlatFileIn = $sqliteFileToDumpFlatFileIn;
    }

    /**
     * @return \PDO
     * @throws \Symfony\Component\Filesystem\Exception\FileNotFoundException
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getConnection(): \PDO
    {
        switch ($this->databaseType) {
            case 'sqlite3':
                return (new Sqlite3Adapter($this->databaseFile))->getPdoInstance();
                break;
            case 'flatfile':
                return (new JsonAdapter($this->databaseFile, $this->sqliteFileToDumpFlatFileIn))->getPdoInstance();
                break;
            default:
                throw new \InvalidArgumentException('DB Type not supported, yet');
                break;
        }
    }
}
