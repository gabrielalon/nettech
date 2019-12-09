<?php

namespace App\Application\Gallery\Query\ReadModel\Entity;

use N3ttech\Messaging\Query\Query;

class AssetCollection extends \ArrayIterator implements Query\ViewableCollection
{
    /**
     * @param Asset $user
     */
    public function add(Query\Viewable $user): void
    {
        $this->offsetSet($user->identifier(), $user);
    }

    /**
     * @param string $uuid
     *
     * @return Asset
     */
    public function get(string $uuid): Asset
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
     * @return Asset[]
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

        /** @var Asset $asset */
        foreach ($this->getArrayCopy() as $asset) {
            $galleries[] = [
                'uuid' => $asset->identifier(),
                'type' => $asset->getType(),
                'filename' => $asset->getFilename()
            ];
        }

        return $galleries;
    }
}
