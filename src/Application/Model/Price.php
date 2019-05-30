<?php

namespace App\Application\Model;

class Price
{
    /**
     * @var int
     */
    private $nett;

    /**
     * @var int
     */
    private $gross;

    /**
     * @var int
     */
    private $tax;

    /**
     * @var string
     */
    private $currency;

    public function __construct(int $nett, int $gross, int $tax, string $currency)
    {
        $this->nett = $nett;
        $this->gross = $gross;
        $this->tax = $tax;
        $this->currency = $currency;
    }

    public function getNett(): int
    {
        return $this->nett;
    }

    public function getGross(): int
    {
        return $this->gross;
    }

    public function getTax(): int
    {
        return $this->tax;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
