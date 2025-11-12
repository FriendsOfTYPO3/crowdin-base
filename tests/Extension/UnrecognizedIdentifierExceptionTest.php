<?php

declare(strict_types=1);


namespace FriendsOfTYPO3\CrowdinBase\Tests\Extension;

use FriendsOfTYPO3\CrowdinBase\Extension\UnrecognizedIdentifierException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(UnrecognizedIdentifierException::class)]
final class UnrecognizedIdentifierExceptionTest extends TestCase
{
    #[Test]
    public function fromInvalidIdentifier(): void
    {
        $actual = UnrecognizedIdentifierException::fromInvalidIdentifier('some-identifier');

        self::assertSame('Identifier "some-identifier" not allowed!', $actual->getMessage());
        self::assertSame(1762860658, $actual->getCode());
    }
}
