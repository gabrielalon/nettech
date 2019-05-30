<?php

namespace App\Application\Model\PurchaseTransaction;

use App\Infrastructure\Common\AbstractCollection;

class Items extends AbstractCollection
{
    protected function isValid($value): bool
    {
        return $value instanceof Item;
    }
}
