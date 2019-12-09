<?php

namespace App\Application\Gallery\Seek\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static OrderSort ASC()
 * @method static OrderSort DESC()
 */
class OrderSort extends Enum
{
    const ASC = 'ASC';
    const DESC = 'DESC';
}