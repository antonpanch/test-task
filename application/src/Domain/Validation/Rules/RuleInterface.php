<?php

declare(strict_types=1);

namespace App\Domain\Validation\Rules;

interface RuleInterface
{
    public function validate(string $fieldName, $fieldValue): void;
}
