<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\User;

interface PasswordInterface
{
    public function getValue(): string;
}
