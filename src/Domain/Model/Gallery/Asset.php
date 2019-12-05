<?php

namespace App\Domain\Model\Gallery;

interface Asset
{
    /**
     * @return Enum\AssetType
     */
    public function getType(): Enum\AssetType;
}
