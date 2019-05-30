<?php

namespace App\Infrastructure\Testing;

abstract class AbstractHelper
{
    protected function runAssertions(array $assertions, object $object, array $data): void
    {
        foreach ($data as $subject => $expectedValue) {
            $assertions[$subject]($expectedValue, $object);
        }
    }
}
