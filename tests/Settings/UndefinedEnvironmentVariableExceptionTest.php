<?php

declare(strict_types=1);


namespace FriendsOfTYPO3\CrowdinBase\Tests\Settings;

use FriendsOfTYPO3\CrowdinBase\Settings\UndefinedEnvironmentVariableException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(UndefinedEnvironmentVariableException::class)]
final class UndefinedEnvironmentVariableExceptionTest extends TestCase
{
    #[Test]
    public function fromEnvironmentKey(): void
    {
        $actual = UndefinedEnvironmentVariableException::fromEnvironmentKey('some-key');

        self::assertSame(
            'The environment variable "some-key" is not defined as environment variable (either in .env file or as environment variable). Have a look into the .env.example file for more details.',
            $actual->getMessage(),
        );
        self::assertSame(1762853210, $actual->getCode());
    }
}
