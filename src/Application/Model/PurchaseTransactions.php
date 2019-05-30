<?php

namespace App\Application\Model;

use App\Infrastructure\Common\AbstractCollection;

class PurchaseTransactions extends AbstractCollection
{
    protected function isValid($value): bool
    {
        return $value instanceof PurchaseTransaction;
    }
}
