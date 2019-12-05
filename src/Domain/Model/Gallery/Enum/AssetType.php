<?php

namespace App\Domain\Model\Gallery\Enum;

/**
 * @method static AssetType IMAGE()
 * @method static AssetType VIDEO()
 */
class AssetType extends \MyCLabs\Enum\Enum
{
    private const IMAGE = 'image';
    private const VIDEO = 'video';
}
