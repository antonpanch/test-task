<?php

namespace App\Tests\Unit\Application\Password;

use App\Application\Password\PasswordEncrypter;
use App\Domain\ValueObject\User\Password;
use PHPUnit\Framework\TestCase;

class PasswordEncrypterTest extends TestCase
{
    public const TEST_SECRET_KEY = 'test secret key';
    public const TEST_PASSWORD = 'testpass';

    public function testEncryptPassword(): void
    {
        $passwordEncrypter = new PasswordEncrypter(self::TEST_SECRET_KEY);
        $encryptedPassword = $passwordEncrypter->encryptPassword(new Password(self::TEST_PASSWORD));
        $secondEncryptedPassword = $passwordEncrypter->encryptPassword(new Password(self::TEST_PASSWORD));
        $this->assertNotEquals($encryptedPassword, $secondEncryptedPassword);

        $this->assertNotEquals(self::TEST_PASSWORD, $encryptedPassword);
        $this->assertNotEmpty($encryptedPassword);
        $this->assertNotNull($encryptedPassword);
    }

    public function testDecryptPassword(): void
    {
        $passwordEncrypter = new PasswordEncrypter(self::TEST_SECRET_KEY);
        $encryptedPassword = $passwordEncrypter->encryptPassword(new Password(self::TEST_PASSWORD));
        $decryptedPassword = $passwordEncrypter->decryptPassword($encryptedPassword);
        $this->assertEquals(self::TEST_PASSWORD, $decryptedPassword->getValue());
    }
}
