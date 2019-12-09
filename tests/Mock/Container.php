<?php

namespace App\Tests\Mock;

use Psr\Container as PsrContainer;

class Container implements PsrContainer\ContainerInterface
{
    /** @var array */
    protected $map;

    /**
     * @param string $id
     * @param mixed  $value
     */
    public function register(string $id, $value): void
    {
        if (null === $this->map) {
            $this->map = [];
        }

        $this->map[$id] = $value;
    }

    /**
     * @param string $id
     *
     * @return mixed
     */
    public function get($id)
    {
        if (false === $this->has($id)) {
            throw new \InvalidArgumentException(\sprintf('Container does not have given service %s', $id));
        }

        return $this->map[$id];
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function has($id)
    {
        return isset($this->map[$id]);
    }
}
