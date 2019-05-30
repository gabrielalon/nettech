<?php

namespace App\Application\Model\PurchaseTransaction;

use App\Application\Model\Price;

class Item
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $transactionId;

    /**
     * @var string
     */
    private $sku;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $option;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var Price
     */
    private $price;

    public function __construct(
        string $id,
        string $transactionId,
        string $sku,
        string $name,
        string $option,
        int $quantity,
        Price $price
    ) {
        $this->id = $id;
        $this->transactionId = $transactionId;
        $this->sku = $sku;
        $this->name = $name;
        $this->option = $option;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOption(): string
    {
        return $this->option;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }
}
