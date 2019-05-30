<?php

namespace App\Domain\Role\ValueObject;

use Assert\Assertion;

final class Permission
{
    /**
     * @var string
     */
    private $action;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var bool
     */
    private $isPublic;

    public function __construct(string $action, string $subject, bool $isPublic)
    {
        Assertion::notEmpty($action, 'Action cannot be empty');
        Assertion::notEmpty($subject, 'Subject cannot be empty');

        $this->action = $action;
        $this->subject = $subject;
        $this->isPublic = $isPublic;
    }

    public function equals(self $permission): bool
    {
        return $this->getAction() === $permission->getAction() &&
            $this->getSubject() === $permission->getSubject() &&
            $this->isPublic() === $permission->isPublic();
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function isPublic(): bool
    {
        return $this->isPublic;
    }

    public function __toString(): string
    {
        return sprintf('%s.%s', $this->getSubject(), $this->getAction());
    }
}
