<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Password;

use App\Application\Password\HashedPasswordStrategy;
use App\Domain\ValueObject\User\EmptyPassword;
use App\Domain\ValueObject\User\Password;
use PHPUnit\Framework\TestCase;

class HashedPasswordStrategyTest extends TestCase
{
    private const TEST_PASSWORD = 'test-1';
    private const TEST_PASSWORD_2 = 'test-2';
    private const TEST_HASH = 'test hash';

    public function testGetModifiedVersion(): void
    {
        $hashedPasswordStrategy = new HashedPasswordStrategy();
        $hash = $hashedPasswordStrategy->getModifiedVersion(new Password(self::TEST_PASSWORD));
        $this->assertTrue(
            $hashedPasswordStrategy->verifyPasswordEqualsPasswordInModifiedVersion(
            new Password(self::TEST_PASSWORD),
                $hash
            )
        );
    }

    public function testGetPasswordFromModifiedVersion(): void
    {
        $hashedPasswordStrategy = new HashedPasswordStrategy();
        $this->assertEquals(
            (new EmptyPassword())->getValue(),
            $hashedPasswordStrategy->getPasswordFromModifiedVersion(self::TEST_HASH)->getValue()
        );
    }

    public function testVerifyPasswordEqualsPasswordInModifiedVersion(): void
    {
        $hashedPasswordStrategy = new HashedPasswordStrategy();
        $hash = $hashedPasswordStrategy->getModifiedVersion(new Password(self::TEST_PASSWORD_2));
        $this->assertTrue(
            $hashedPasswordStrategy->verifyPasswordEqualsPasswordInModifiedVersion(
            new Password(self::TEST_PASSWORD_2),
                $hash
            )
        );
        $this->assertFalse($hashedPasswordStrategy->verifyPasswordEqualsPasswordInModifiedVersion(
            new Password('wrong'),
            $hash
        ));
        $this->assertFalse($hashedPasswordStrategy->verifyPasswordEqualsPasswordInModifiedVersion(
            new Password(self::TEST_PASSWORD_2),
            'wrong hash'
        ));
    }
}
