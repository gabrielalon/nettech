<?php

namespace App\Application\Message\Auth\Query;

use App\Application\Message\Auth\Query\FindUsers\Filters;
use Symfony\Component\Serializer\Normalizer\DenormalizableInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class FindUsers implements DenormalizableInterface
{
    /**
     * @var array
     */
    private $filters;

    public function getFilters(): Filters
    {
        return new Filters($this->filters);
    }

    public function denormalize(DenormalizerInterface $denormalizer, $data, $format = null, array $context = [])
    {
        $this->filters = $data['filters'];
    }
}
