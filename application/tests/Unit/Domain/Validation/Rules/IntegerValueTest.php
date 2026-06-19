<?php

namespace App\Tests\Unit\Domain\Validation\Rules;

use App\Domain\Exception\DomainValidationException;
use App\Domain\Validation\Rules\IntegerValue;
use PHPUnit\Framework\TestCase;

class IntegerValueTest extends TestCase
{
    public function testValidateNull()
    {
        $integerValueRule = new IntegerValue();
        $this->expectException(DomainValidationException::class);
        $integerValueRule->validate('field', null);
    }

    public function testValidateEmptyString()
    {
        $integerValueRule = new IntegerValue();
        $this->expectException(DomainValidationException::class);
        $integerValueRule->validate('field', '');
    }

    public function testValidateFloatAsString()
    {
        $integerValueRule = new IntegerValue();
        $this->expectException(DomainValidationException::class);
        $integerValueRule->validate('field', '3.14');
    }

    public function testValidateIntegerAsString()
    {
        $integerValueRule = new IntegerValue();
        $this->expectNotToPerformAssertions();
        $integerValueRule->validate('field', '5');
    }

    public function testValidateInteger()
    {
        $integerValueRule = new IntegerValue();
        $this->expectNotToPerformAssertions();
        $integerValueRule->validate('field', 5);
    }
}
