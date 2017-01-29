<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 18.01.17
 * Time: 20:31
 */

namespace mgbs\Library\Database;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class DatabaseFactory
{
    /**
     * @var string $databaseType
     */
    private $databaseType;
    /**
     * @var string $host Should contain hostname. If flatfile, or sqllite3 leave null and use $filename
     */
    private $host;

    /**
     * @var string $databaseFile Should contain filename for database
     */
    private $databaseFile;
    /**
     * @var string
     */
    private $user;
    /**
     * @var string
     */
    private $password;
    /**
     * @var int
     */
    private $port;

    /**
     * @param string $databaseType
     * @param string|null $databaseFile
     * @param string|null $host
     * @param string|null $user
     * @param string|null $password
     * @param int|null $port
     */
    public function __construct(
        string $databaseType,
        string $databaseFile = null,
        string $host = null,
        string $user = null,
        string $password = null,
        int $port = null
    ) {

        $this->databaseType = $databaseType;
        $this->databaseFile = $databaseFile;
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->port = $port;
    }

    /**
     * @return \PDO
     * @throws \Symfony\Component\Filesystem\Exception\FileNotFoundException
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getConnection(): \PDO
    {
        if ('sqlite3' === $this->databaseType) {
            return (new Sqlite3Adapter($this->databaseFile))->getPdoInstance();
        } else {
            throw new \InvalidArgumentException('DB Type not supported, yet');
        }
    }
}
