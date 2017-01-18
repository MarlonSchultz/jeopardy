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
    private $datebaseType;
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

    public function __construct(
        string $databaseType,
        string $host = null,
        string $user = null,
        string $password = null,
        int $port = null
    ) {

        $this->datebaseType = $databaseType;
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->port = $port;
    }

    /**
     * @return bool|\PDO
     */
    public function getConnection()
    {
        switch ($this->datebaseType) {
            case 'sqlite3':
                return new \PDO('sqlite:' . $this->host);
                break;
            default:
                return false;
                break;
        }
    }
}