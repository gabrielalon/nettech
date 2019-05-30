<?php

namespace App\Domain\Country\Command;

final class Create
{
    /**
     * @var string
     */
    private $countryId;

    /**
     * @var string
     */
    private $name;

    public function __construct(string $countryId, string $name)
    {
        $this->countryId = $countryId;
        $this->name = $name;
    }

    public function getCountryId(): string
    {
        return $this->countryId;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
