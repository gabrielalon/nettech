<?php

namespace App\Infrastructure\Messenger\Message;

use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

abstract class AbstractMessage implements NormalizableInterface
{
    /**
     * @var object|null
     */
    private $model;

    public function __construct(?object $model)
    {
        $this->model = $model;
    }

    public function normalize(NormalizerInterface $normalizer, $format = null, array $context = [])
    {
        return $normalizer->normalize($this->model);
    }
}
