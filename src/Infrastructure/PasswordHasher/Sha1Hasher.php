<?php

namespace App\Infrastructure\PasswordHasher;

use Symfony\Component\Security\Core\Encoder\BasePasswordEncoder;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class Sha1Hasher extends BasePasswordEncoder implements PasswordHasherInterface
{
    /**
     * {@inheritdoc}
     */
    public function hash(string $password): string
    {
        return sha1($password);
    }

    /**
     * {@inheritdoc}
     */
    public function encodePassword($raw, $salt)
    {
        if ($this->isPasswordTooLong($raw)) {
            throw new BadCredentialsException('Invalid password.');
        }

        return sha1($this->mergePasswordAndSalt($raw, $salt));
    }

    /**
     * {@inheritdoc}
     */
    public function isPasswordValid($encoded, $raw, $salt)
    {
        if ($this->isPasswordTooLong($raw)) {
            return false;
        }

        try {
            $pass2 = $this->encodePassword($raw, $salt);
        } catch (BadCredentialsException $e) {
            return false;
        }

        return $this->comparePasswords($encoded, $pass2);
    }

    /**
     * Merges a password and a salt.
     *
     * @param string $password the password to be used
     * @param string $salt     the salt to be used
     *
     * @return string a merged password and salt
     *
     * @throws \InvalidArgumentException
     */
    protected function mergePasswordAndSalt($password, $salt)
    {
        if (empty($salt)) {
            return $password;
        }

        return $salt.$password;
    }

    /**
     * {@inheritdoc}
     */
    public function getEncoderName()
    {
        return 'sha1';
    }
}
