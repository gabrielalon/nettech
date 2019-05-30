<?php

namespace App\Domain\User\QueryHandler;

use App\Domain\User\Query\FindUsers;
use App\Domain\User\ReadModel\Repository\UserRepositoryInterface;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class FindUsersHandler implements MessageHandlerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(FindUsers $query): array
    {
        $criteria = Criteria::create();
        $filters = $query->getFilters();

        if ($filters->hasUsername()) {
            $username = $filters->getUsername();

            if ($username->hasEquals()) {
                $criteria->andWhere(Criteria::expr()->eq('login', $username->getEquals()));
            }
        }

        return $this->userRepository->findBy($criteria);
    }
}
