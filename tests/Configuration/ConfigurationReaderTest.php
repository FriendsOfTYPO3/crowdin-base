<?php

declare(strict_types=1);


namespace FriendsOfTYPO3\CrowdinBase\Tests\Configuration;

use FriendsOfTYPO3\CrowdinBase\Configuration\ConfigurationReader;
use FriendsOfTYPO3\CrowdinBase\Configuration\Exception\InvalidConfigurationFileException;
use FriendsOfTYPO3\CrowdinBase\Configuration\Exception\UnavailableConfigurationFileException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ConfigurationReader::class)]
final class ConfigurationReaderTest extends TestCase
{
    #[Test]
    public function readReturnsConfigurationCorrectly(): void
    {
        $subject = new ConfigurationReader(__DIR__ . '/Fixture/configuration.json');

        $actual = $subject->read();

        self::assertCount(3, $actual);

        self::assertSame('typo3-extension-news', $actual[0]->identifier);
        self::assertSame(368353, $actual[0]->id);
        self::assertSame('news', $actual[0]->name);
        self::assertSame(["km", "pt-BR", "ro", "ru"], $actual[0]->languages);

        self::assertSame('typo3-cms', $actual[1]->identifier);
        self::assertSame(368815, $actual[1]->id);
        self::assertSame('typo3-cms', $actual[1]->name);
        self::assertSame(["de", "el", "eo", "es-ES"], $actual[1]->languages);

        self::assertSame('typo3-extension-ttaddress', $actual[2]->identifier);
        self::assertSame(369561, $actual[2]->id);
        self::assertSame('tt_address', $actual[2]->name);
        self::assertSame(["pt-BR", "pt-PT", "ro", "sv-SE", "uk"], $actual[2]->languages);
    }


    #[Test]
    public function readWithNonExistentFileThrowsException(): void
    {
        $this->expectException(UnavailableConfigurationFileException::class);
        $this->expectExceptionCode(1762870302);

        $subject = new ConfigurationReader(__DIR__ . '/Fixture/non-existent-configuration.json');

        $subject->read();
    }

    #[Test]
    public function readWithNonReadableFileThrowsException(): void
    {
        $this->expectException(UnavailableConfigurationFileException::class);
        $this->expectExceptionCode(1762870303);

        $configurationFileName = '/tmp/crowdin-base-configuration-reader-test-' . uniqid();
        touch($configurationFileName);
        chmod($configurationFileName, 0200);

        $subject = new ConfigurationReader($configurationFileName);
        $subject->read();
    }

    #[Test]
    public function readWithFileIsNotJsonThrowsException(): void
    {
        $this->expectException(InvalidConfigurationFileException::class);
        $this->expectExceptionCode(1762870304);

        $subject = new ConfigurationReader(__DIR__ . '/Fixture/invalid_json_configuration.json');

        $subject->read();
    }

    #[Test]
    public function readWithMissingProjectsKeyThrowsException(): void
    {
        $this->expectException(InvalidConfigurationFileException::class);
        $this->expectExceptionCode(1762870305);

        $subject = new ConfigurationReader(__DIR__ . '/Fixture/missing_projects_key_configuration.json');

        $subject->read();
    }
}
