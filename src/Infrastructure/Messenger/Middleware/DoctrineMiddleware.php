<?php

namespace App\Infrastructure\Messenger\Middleware;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class DoctrineMiddleware implements MiddlewareInterface
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        foreach ($this->managerRegistry->getManagers() as $manager) {
            $manager->clear();

            if ($manager instanceof EntityManager) {
                $connection = $manager->getConnection();

                if (!$connection->ping()) {
                    $connection->close();
                    $connection->connect();
                }
            }
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
