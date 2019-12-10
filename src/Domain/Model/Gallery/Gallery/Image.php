<?php

namespace App\Domain\Model\Gallery\Gallery;

use App\Domain\Model\Gallery\Asset;
use App\Domain\Model\Gallery\Enum;
use N3ttech\Valuing as VO;

final class Image extends VO\VO implements Asset
{
    /**
     * @param string $image
     *
     * @return Asset
     *
     * @throws \Assert\AssertionFailedException
     */
    public static function fromImage(string $image): Asset
    {
        return new Image($image);
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): Enum\AssetType
    {
        return Enum\AssetType::IMAGE();
    }

    /**
     * {@inheritdoc}
     */
    protected function guard($value): void
    {
        \Assert\Assertion::string($value);
    }
}
