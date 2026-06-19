<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class EntityNotFoundException extends DomainValidationException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
