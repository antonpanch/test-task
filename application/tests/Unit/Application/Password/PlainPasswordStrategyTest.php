<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Password;

use App\Application\Password\PlainPasswordStrategy;
use App\Domain\ValueObject\User\Password;
use PHPUnit\Framework\TestCase;

class PlainPasswordStrategyTest extends TestCase
{
    private const TEST_PASSWORD = 'test1';

    public function testGetModifiedVersion(): void
    {
        $plainPasswordStrategy = new PlainPasswordStrategy();
        $this->assertEquals(
            self::TEST_PASSWORD,
            $plainPasswordStrategy->getModifiedVersion(new Password(self::TEST_PASSWORD))
        );
    }

    public function testGetPasswordFromModifiedVersion(): void
    {
        $plainPasswordStrategy = new PlainPasswordStrategy();
        $this->assertEquals(
            self::TEST_PASSWORD,
            $plainPasswordStrategy->getPasswordFromModifiedVersion(self::TEST_PASSWORD)->getValue()
        );
    }

    public function testVerifyPasswordEqualsPasswordInModifiedVersion()
    {
        $plainPasswordStrategy = new PlainPasswordStrategy();
        $this->assertTrue(
            $plainPasswordStrategy->verifyPasswordEqualsPasswordInModifiedVersion(
                new Password(self::TEST_PASSWORD),
                self::TEST_PASSWORD
            )
        );
    }
}
