<?php

namespace App\Infrastructure\Query\Gallery;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class DoctrineQuery extends ServiceEntityRepository
{
    /**
     * @param string $sql
     * @param string $param
     * @param $value
     */
    protected function injectParameter(string &$sql, string $param, $value): void
    {
        $param = sprintf('{%s}', $param);
        $sql = str_replace([$param], [$value], $sql);
    }

    /**
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function calculateTotalRows(): int
    {
        $connection = $this->getEntityManager()->getConnection();

        $sql = 'SELECT FOUND_ROWS()';

        $statement = $connection->prepare($sql);
        $statement->execute();

        return (int) $statement->fetchColumn(0);
    }
}