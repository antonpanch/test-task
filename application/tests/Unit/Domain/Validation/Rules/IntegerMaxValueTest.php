<?php

namespace App\Tests\Unit\Domain\Validation\Rules;

use App\Domain\Exception\DomainValidationException;
use App\Domain\Validation\Rules\IntegerMaxValue;
use PHPUnit\Framework\TestCase;

class IntegerMaxValueTest extends TestCase
{
    public function testValidateNull()
    {
        $integerMaxValueRule = new IntegerMaxValue(5);
        $this->expectException(DomainValidationException::class);
        $integerMaxValueRule->validate('field', null);
    }

    public function testValidateEmptyString()
    {
        $integerMaxValueRule = new IntegerMaxValue(5);
        $this->expectException(DomainValidationException::class);
        $integerMaxValueRule->validate('field', '');
    }

    public function testValidateString()
    {
        $integerMaxValueRule = new IntegerMaxValue(5);
        $this->expectException(DomainValidationException::class);
        $integerMaxValueRule->validate('field', 'a');
    }

    public function testValidateIntegerAsString()
    {
        $integerMaxValueRule = new IntegerMaxValue(5);
        $this->expectNotToPerformAssertions();
        $integerMaxValueRule->validate('field', '1');
    }

    public function testValidateIntegerLessThanMax()
    {
        $integerMaxValueRule = new IntegerMaxValue(5);
        $this->expectNotToPerformAssertions();
        $integerMaxValueRule->validate('field', 4);
    }

    public function testValidateIntegerLessEqualMax()
    {
        $integerMaxValueRule = new IntegerMaxValue(5);
        $this->expectNotToPerformAssertions();
        $integerMaxValueRule->validate('field', 5);
    }

    public function testValidateIntegerMoreThanMax()
    {
        $integerMaxValueRule = new IntegerMaxValue(5);
        $this->expectException(DomainValidationException::class);
        $integerMaxValueRule->validate('field', 7);
    }
}
