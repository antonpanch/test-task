<?php

namespace App\Tests\Unit\Domain\Validation\Rules;

use App\Domain\Exception\DomainValidationException;
use App\Domain\Validation\Rules\NotEmpty;
use PHPUnit\Framework\TestCase;

class NotEmptyTest extends TestCase
{
    public function testValidateNull()
    {
        $notEmptyRule = new NotEmpty();
        $this->expectException(DomainValidationException::class);
        $notEmptyRule->validate('field', null);
    }

    public function testValidateFalse()
    {
        $notEmptyRule = new NotEmpty();
        $this->expectNotToPerformAssertions();
        $notEmptyRule->validate('field', false);
    }

    public function testValidateTrue()
    {
        $notEmptyRule = new NotEmpty();
        $this->expectNotToPerformAssertions();
        $notEmptyRule->validate('field', true);
    }

    public function testValidateEmptyString()
    {
        $notEmptyRule = new NotEmpty();
        $this->expectNotToPerformAssertions();
        $notEmptyRule->validate('field', '');
    }

    public function testValidateNotEmptyString()
    {
        $notEmptyRule = new NotEmpty();
        $this->expectNotToPerformAssertions();
        $notEmptyRule->validate('field', 'a');
    }

    public function testValidateInteger()
    {
        $notEmptyRule = new NotEmpty();
        $this->expectNotToPerformAssertions();
        $notEmptyRule->validate('field', 10);
    }
}
