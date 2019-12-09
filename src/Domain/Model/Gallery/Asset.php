<?php

namespace App\Domain\Model\Gallery;

use N3ttech\Valuing\Stringify;

interface Asset extends Stringify
{
    /**
     * @return Enum\AssetType
     */
    public function getType(): Enum\AssetType;
}
