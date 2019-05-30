<?php

namespace App\Tests;

use App\Kernel;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;

class DatabaseTestListener implements TestListener
{
    use TestListenerDefaultImplementation;

    public function startTest(Test $test)
    {
        if ($test instanceof TestCase) {
            // @var array $annotation
            foreach ($test->getAnnotations()['method'] as $name => $arguments) {
                if ('resetDatabase' === $name) {
                    $kernel = new Kernel(getenv('APP_ENV'), false);

                    $application = new Application($kernel);
                    $application->setAutoExit(false);
                    $application->run(new StringInput('doctrine:schema:drop --force'));
                    $application->run(new StringInput('doctrine:schema:create'));
                }
            }
        }
    }
}
