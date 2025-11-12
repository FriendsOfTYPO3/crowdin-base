<?php

declare(strict_types=1);


namespace FriendsOfTYPO3\CrowdinBase\Tests\Configuration;

use _PHPStan_6597ef616\Nette\Utils\JsonException;
use FriendsOfTYPO3\CrowdinBase\Configuration\Exception\ConfigurationFileWriteException;
use FriendsOfTYPO3\CrowdinBase\Configuration\ConfigurationReader;
use FriendsOfTYPO3\CrowdinBase\Configuration\ConfigurationWriter;
use FriendsOfTYPO3\CrowdinBase\Configuration\Exception\InvalidConfigurationDataException;
use FriendsOfTYPO3\CrowdinBase\Configuration\Entity\Project;
use FriendsOfTYPO3\CrowdinBase\Extension\ExtensionKeyGenerator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ConfigurationWriter::class)]
final class ConfigurationWriterTest extends TestCase
{
    #[Test]
    public function writeCreatesConfigurationFileCorrectly(): void
    {
        $configurationFile = '/tmp/crowdin-base-configuration-writer-test/' . uniqid();
        $projects = [
            new Project(1, 'typo3-extension-some-identifier', 'typo3 extension news', ['de']),
            new Project(2, 'typo3-extension-another-identifier', 'typo3 extension tt_address', ['fr', 'it']),
        ];
        $subject = new ConfigurationWriter(new ExtensionKeyGenerator(), $configurationFile, []);

        $subject->write($projects);
        $configurationReader = new ConfigurationReader($configurationFile);
        $actual = $configurationReader->read();

        self::assertCount(2, $actual);

        self::assertSame(1, $actual[0]->id);
        self::assertSame('typo3-extension-some-identifier', $actual[0]->identifier);
        self::assertSame('news', $actual[0]->name);
        self::assertSame(['de'], $actual[0]->languages);

        self::assertSame(2, $actual[1]->id);
        self::assertSame('typo3-extension-another-identifier', $actual[1]->identifier);
        self::assertSame('tt_address', $actual[1]->name);
        self::assertSame(['fr', 'it'], $actual[1]->languages);

        unlink($configurationFile);
    }

    #[Test]
    public function writeCreatesConfigurationFileWithoutGivenSkippedProjectsCorrectly(): void
    {
        $configurationFile = '/tmp/crowdin-base-configuration-writer-test/' . uniqid();
        $projects = [
            new Project(1, 'typo3-extension-some-identifier', 'typo3 extension news', ['de']),
            new Project(2, 'typo3-extension-another-identifier', 'typo3 extension tt_address', ['fr', 'it']),
        ];
        $subject = new ConfigurationWriter(new ExtensionKeyGenerator(), $configurationFile, ['typo3-extension-some-identifier']);

        $subject->write($projects);
        $configurationReader = new ConfigurationReader($configurationFile);
        $actual = $configurationReader->read();

        self::assertCount(1, $actual);
        self::assertSame(2, $actual[0]->id);

        unlink($configurationFile);
    }

    #[Test]
    public function writeThrowsErrorOnInvalidJson(): void
    {
        $this->expectException(InvalidConfigurationDataException::class);

        $configurationFile = '/tmp/crowdin-base-configuration-writer-test/' . uniqid();
        $projects = [
            new Project(1, 'typo3-extension-some-identifier', "typo3 extension news\xB1\x31", ['de']),
        ];
        $subject = new ConfigurationWriter(new ExtensionKeyGenerator(), $configurationFile, []);

        $subject->write($projects);
    }

    #[Test]
    public function writeThrowsErrorIfConfigurationFileCannotBeWritten(): void
    {
        $this->expectException(ConfigurationFileWriteException::class);

        $configurationFile = \sprintf('/tmp/crowdin-base-configuration-writer-test-%s/configuration.json', uniqid());
        mkdir(dirname($configurationFile));
        chmod(dirname($configurationFile), 0500);
        $projects = [
            new Project(1, 'typo3-extension-some-identifier', "typo3 extension news", ['de']),
        ];

        $subject = new ConfigurationWriter(new ExtensionKeyGenerator(), $configurationFile, []);

        $subject->write($projects);
    }
}
