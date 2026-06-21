<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Password;

use App\Application\Password\EncryptedPasswordStrategy;
use App\Application\Password\PasswordEncrypter;
use App\Domain\ValueObject\User\Password;
use PHPUnit\Framework\TestCase;

class EncryptedPasswordStrategyTest extends TestCase
{
    const TEST_PASSWORD = 'test1';
    const MOCK_ENCRYPTED_PASSWORD_VALUE = 'encrypted pass';

    public function testGetModifiedVersion(): void
    {
        $passwordEncrypter = $this->createStub(PasswordEncrypter::class);
        $passwordEncrypter->method('encryptPassword')->willReturn(self::MOCK_ENCRYPTED_PASSWORD_VALUE);
        $encryptedPasswordStrategy = new EncryptedPasswordStrategy($passwordEncrypter);
        $this->assertEquals(
            self::MOCK_ENCRYPTED_PASSWORD_VALUE,
            $encryptedPasswordStrategy->getModifiedVersion(new Password(self::TEST_PASSWORD))
        );;
    }

    public function testGetPasswordFromModifiedVersion(): void
    {
        $passwordEncrypter = $this->createStub(PasswordEncrypter::class);
        $passwordEncrypter->method('decryptPassword')->willReturn(new Password(self::TEST_PASSWORD));
        $encryptedPasswordStrategy = new EncryptedPasswordStrategy($passwordEncrypter);
        $this->assertEquals(
            new Password(self::TEST_PASSWORD),
            $encryptedPasswordStrategy->getPasswordFromModifiedVersion(self::MOCK_ENCRYPTED_PASSWORD_VALUE)
        );
    }

    public function testVerifyPasswordEqualsPasswordInModifiedVersion(): void
    {
        $passwordEncrypter = $this->createStub(PasswordEncrypter::class);
        $passwordEncrypter->method('decryptPassword')->willReturn(new Password(self::TEST_PASSWORD));
        $encryptedPasswordStrategy = new EncryptedPasswordStrategy($passwordEncrypter);
        $this->assertTrue(
            $encryptedPasswordStrategy->verifyPasswordEqualsPasswordInModifiedVersion(
            new Password(self::TEST_PASSWORD),
                self::MOCK_ENCRYPTED_PASSWORD_VALUE
            )
        );
    }
}
