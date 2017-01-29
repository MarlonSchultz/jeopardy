<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 29.01.17
 * Time: 18:01
 */
namespace mgbs\Library\Database;

interface DatabaseAdapterInterface
{
    /**
     * Initializes the PDO instance
     *
     * @return mixed
     */
    public function initPdoInstance();

    /**
     * @return \PDO
     */
    public function getPdoInstance() : \PDO;
}
