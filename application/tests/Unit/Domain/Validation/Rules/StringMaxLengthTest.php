<?php

namespace App\Tests\Unit\Domain\Validation\Rules;

use App\Domain\Exception\DomainValidationException;
use App\Domain\Validation\Rules\StringMaxLength;
use PHPUnit\Framework\TestCase;

class StringMaxLengthTest extends TestCase
{
    public function testValidateNull()
    {
        $stringMaxLengthRule = new StringMaxLength(5);
        $this->expectException(DomainValidationException::class);
        $stringMaxLengthRule->validate('field', null);
    }

    public function testValidateInteger()
    {
        $stringMaxLengthRule = new StringMaxLength(5);
        $this->expectException(DomainValidationException::class);
        $stringMaxLengthRule->validate('field', 3);
    }

    public function testValidateStringWithLengthLessThanMax()
    {
        $stringMaxLengthRule = new StringMaxLength(5);
        $this->expectNotToPerformAssertions();
        $stringMaxLengthRule->validate('field', 'aaa');
    }

    public function testValidateStringWithLengthEqualMax()
    {
        $stringMaxLengthRule = new StringMaxLength(5);
        $this->expectNotToPerformAssertions();
        $stringMaxLengthRule->validate('field', 'aaaaa');
    }

    public function testValidateStringWithLengthMoreThanMax()
    {
        $stringMaxLengthRule = new StringMaxLength(5);
        $this->expectException(DomainValidationException::class);
        $stringMaxLengthRule->validate('field', 'aaaaaa');
    }
}
