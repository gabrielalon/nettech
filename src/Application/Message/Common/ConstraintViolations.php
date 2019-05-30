<?php

namespace App\Application\Message\Common;

use App\Infrastructure\Messenger\Message\AbstractMessage;
use App\Infrastructure\Messenger\Message\ResultInterface;

class ConstraintViolations extends AbstractMessage implements ResultInterface
{
}
