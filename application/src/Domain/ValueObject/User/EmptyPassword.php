<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\User;

class EmptyPassword implements PasswordInterface
{
    public function getValue(): string
    {
        return '';
    }
}
