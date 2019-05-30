<?php

namespace App\Domain\Role\Command;

final class RevokePermission
{
    private $id;
    private $subject;
    private $action;

    public function __construct(string $id, string $subject, string $action)
    {
        $this->id = $id;
        $this->subject = $subject;
        $this->action = $action;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
