<?php

namespace App\Application\Gallery\Seek\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static GalleryOrder CREATED_AT()
 * @method static GalleryOrder ASSET_COUNTER()
 */
class GalleryOrder extends Enum
{
    const CREATED_AT = 'created_at';
    const ASSET_COUNTER = 'asset_counter';
}