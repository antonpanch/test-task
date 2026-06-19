<?php

namespace App\Tests\Unit\Domain\Validation\Rules;

use App\Domain\Exception\DomainValidationException;
use App\Domain\Validation\Rules\StringMinLength;
use PHPUnit\Framework\TestCase;

class StringMinLengthTest extends TestCase
{
    public function testValidateNull()
    {
        $stringMinLengthRule = new StringMinLength(5);
        $this->expectException(DomainValidationException::class);
        $stringMinLengthRule->validate('field', null);
    }

    public function testValidateInteger()
    {
        $stringMinLengthRule = new StringMinLength(5);
        $this->expectException(DomainValidationException::class);
        $stringMinLengthRule->validate('field', 3);
    }

    public function testValidateStringWithLengthMoreThanMin()
    {
        $stringMinLengthRule = new StringMinLength(5);
        $this->expectNotToPerformAssertions();
        $stringMinLengthRule->validate('field', 'aaaaaa');
    }

    public function testValidateStringWithLengthEqualMin()
    {
        $stringMinLengthRule = new StringMinLength(5);
        $this->expectNotToPerformAssertions();
        $stringMinLengthRule->validate('field', 'aaaaa');
    }

    public function testValidateStringWithLengthLessThanMin()
    {
        $stringMinLengthRule = new StringMinLength(5);
        $this->expectException(DomainValidationException::class);
        $stringMinLengthRule->validate('field', 'aaa');
    }
}
