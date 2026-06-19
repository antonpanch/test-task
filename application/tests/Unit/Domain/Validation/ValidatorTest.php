<?php

namespace App\Tests\Unit\Domain\Validation;

use App\Domain\Validation\Rules\RuleInterface;
use App\Domain\Validation\Validator;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ValidatorTest extends TestCase
{
    public function testValidateWithoutExceptions()
    {
        $rule1 = $this->createMock(RuleInterface::class);
        $rule1->expects($this->once())->method('validate');
        $rule2 = $this->createMock(RuleInterface::class);
        $rule2->expects($this->once())->method('validate');
        $validator = new Validator();
        $validator->validate('test field', 'test value', [$rule1, $rule2]);
    }

    public function testValidateWithException()
    {
        $rule1 = $this->createMock(RuleInterface::class);
        $rule1->expects($this->once())->method('validate');
        $rule2 = $this->createStub(RuleInterface::class);
        $rule2->method('validate')->willThrowException(new RuntimeException('Message'));
        $validator = new Validator();
        $this->expectExceptionMessageIs('Message');
        $validator->validate('test field', 'test value', [$rule1, $rule2]);
    }
}
