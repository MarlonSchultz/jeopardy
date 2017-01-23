<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 18.01.17
 * Time: 20:31
 */

namespace mgbs\Library;

class Database
{
    /**
     * @var string
     */
    private $databaseType;
    /**
     * @var string
     */
    private $host;
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
     * @param string|null $host
     * @param string|null $user
     * @param string|null $password
     * @param int|null $port
     */
    public function __construct(
        string $databaseType,
        string $host = null,
        string $user = null,
        string $password = null,
        int $port = null
    ) {

        $this->databaseType = $databaseType;
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->port = $port;
    }

    /**
     * @return bool|\PDO
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getConnection()
    {
        if ('sqlite3' === $this->databaseType) {
            try {
                return new \PDO('sqlite:' . $this->host);
            } catch (\Exception $e) {
                throw new \RuntimeException('Cannot connect to database: ' . $e->getMessage());
            }
        } else {
            throw new \InvalidArgumentException('DB Type not supported, yet');
        }
    }
}