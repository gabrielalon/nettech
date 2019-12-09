<?php

namespace App\Application\Gallery\Query\ReadModel\Entity;

use N3ttech\Messaging\Query\Query;

class GalleryCollection extends \ArrayIterator implements Query\ViewableCollection
{
    /**
     * @param Gallery $user
     */
    public function add(Query\Viewable $user): void
    {
        $this->offsetSet($user->identifier(), $user);
    }

    /**
     * @param string $uuid
     *
     * @return Gallery
     */
    public function get(string $uuid): Gallery
    {
        return $this->offsetGet($uuid);
    }

    /**
     * @param string $uuid
     *
     * @return bool
     */
    public function has(string $uuid): bool
    {
        return $this->offsetExists($uuid);
    }

    /**
     * @param string $uuid
     */
    public function remove(string $uuid): void
    {
        $this->offsetUnset($uuid);
    }

    /**
     * @return Gallery[]
     */
    public function all(): array
    {
        return $this->getArrayCopy();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $galleries = [];

        /** @var Gallery $gallery */
        foreach ($this->getArrayCopy() as $gallery) {
            $galleries[] = [
                'uuid' => $gallery->identifier(),
                'created_at' => $gallery->getCreatedAt(),
                'source' => $gallery->getSource(),
                'name' => $gallery->getName(),
                'asset_counter' => $gallery->assetCounter()
            ];
        }

        return $galleries;
    }
}
