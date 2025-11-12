<?php

declare(strict_types=1);


namespace FriendsOfTYPO3\CrowdinBase\Tests\Extension;

use FriendsOfTYPO3\CrowdinBase\Extension\ExtensionKeyGenerator;
use FriendsOfTYPO3\CrowdinBase\Extension\UnrecognizedIdentifierException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ExtensionKeyGenerator::class)]
final class ExtensionKeyGeneratorTest extends TestCase
{
    private ExtensionKeyGenerator $subject;

    protected function setUp(): void
    {
        $this->subject = new ExtensionKeyGenerator();
    }

    #[Test]
    #[DataProvider('provider')]
    public function extensionKeyIsReturnedCorrectly(string $identifier, string $name, string $expected): void
    {
        $actual = $this->subject->generate($identifier, $name);

        self::assertSame($expected, $actual);
    }

    public static function provider(): iterable
    {
        yield 'with core identifier' => [
            'identifier' => 'typo3-cms',
            'name' => '',
            'expected' => 'typo3-cms',
        ];

        yield 'with extension' => [
            'identifier' => 'typo3-extension-matomointegrat',
            'name' => 'typo3 extension matomo_integration',
            'expected' => 'matomo_integration',
        ];

        yield 'with extension name with upper letters' => [
            'identifier' => 'typo3-extension-matomointegrat',
            'name' => 'typo3 extension Matomo_Integration',
            'expected' => 'matomo_integration',
        ];

        yield 'with extension and trailing space in name' => [
            'identifier' => 'typo3-extension-news',
            'name' => 'typo3 extension  news ',
            'expected' => 'news',
        ];
    }

    #[Test]
    public function exceptionIsThrownIfIdentifierDoesNotStartWithTypo3Extension(): void
    {
        $this->expectException(UnrecognizedIdentifierException::class);

        $this->subject->generate('some-wrong-identifier', '');
    }

}
