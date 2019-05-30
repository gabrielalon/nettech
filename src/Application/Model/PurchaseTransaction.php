<?php

namespace App\Application\Model;

class PurchaseTransaction
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $currency;

    public function __construct(string $id, string $currency)
    {
        $this->id = $id;
        $this->currency = $currency;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
