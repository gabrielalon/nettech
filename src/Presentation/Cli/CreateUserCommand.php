<?php

namespace App\Presentation\Cli;

use App\Application\User\Service\UserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';

    /** @var UserService */
    private $userService;

    /**
     * CreateUserCommand constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setDescription('Creates a new user.')
            ->setHelp('This command allows you to create a user...')
            ->addArgument('login', InputArgument::REQUIRED, 'User login')
            ->addArgument('password', InputArgument::REQUIRED, 'User password')
        ;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'User Creator',
            '============',
            '',
        ]);

        try {
            $user = $this->userService->createUser(
                $input->getArgument('login'),
                $input->getArgument('password')
            );

            $output->writeln([
                'Successfully created user.',
                'User login: ' . $user->getLogin(),
                ''
            ]);
        } catch (\Exception $e) {
            $output->writeln([
                'User not created.',
                $e->getMessage(),
                $e->__toString(),
                ''
            ]);
        }

        return 1;
    }
}