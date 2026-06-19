<?php

namespace App\Tests\Unit\Domain\Validation\Rules;

use App\Domain\Exception\DomainValidationException;
use App\Domain\Validation\Rules\IntegerMinValue;
use PHPUnit\Framework\TestCase;

class IntegerMinValueTest extends TestCase
{
    public function testValidateNull()
    {
        $integerMinValueRule = new IntegerMinValue(5);
        $this->expectException(DomainValidationException::class);
        $integerMinValueRule->validate('field', null);
    }

    public function testValidateEmptyString()
    {
        $integerMinValueRule = new IntegerMinValue(5);
        $this->expectException(DomainValidationException::class);
        $integerMinValueRule->validate('field', '');
    }

    public function testValidateString()
    {
        $integerMinValueRule = new IntegerMinValue(5);
        $this->expectException(DomainValidationException::class);
        $integerMinValueRule->validate('field', 'a');
    }

    public function testValidateIntegerAsString()
    {
        $integerMinValueRule = new IntegerMinValue(5);
        $this->expectNotToPerformAssertions();
        $integerMinValueRule->validate('field', '7');
    }

    public function testValidateIntegerMoreThanMin()
    {
        $integerMinValueRule = new IntegerMinValue(5);
        $this->expectNotToPerformAssertions();
        $integerMinValueRule->validate('field', 8);
    }

    public function testValidateIntegerLessEqualMin()
    {
        $integerMinValueRule = new IntegerMinValue(5);
        $this->expectNotToPerformAssertions();
        $integerMinValueRule->validate('field', 5);
    }

    public function testValidateIntegerLessThanMin()
    {
        $integerMinValueRule = new IntegerMinValue(5);
        $this->expectException(DomainValidationException::class);
        $integerMinValueRule->validate('field', 4);
    }
}
