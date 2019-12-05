<?php

namespace App\Domain\Model\Gallery\Gallery;

use App\Domain\Model\Gallery\Enum;
use App\Domain\Model\Gallery\Asset;
use N3ttech\Valuing as VO;

final class Video extends VO\VO implements Asset
{
    /**
     * @param string $video
     * @return Video
     * @throws \Assert\AssertionFailedException
     */
    public static function fromVideo(string $video): Video
    {
        return new Video($video);
    }

    /**
     * @inheritDoc
     */
    public function getType(): Enum\AssetType
    {
        return Enum\AssetType::VIDEO();
    }

    /**
     * @inheritDoc
     */
    protected function guard($value): void
    {
        \Assert\Assertion::string($value);
    }
}
