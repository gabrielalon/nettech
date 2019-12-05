<?php

namespace App\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;

abstract class DatabaseConnected
{
    /** @var Connection */
    protected $connection;

    /**
     * DoctrineUserQuery constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
}