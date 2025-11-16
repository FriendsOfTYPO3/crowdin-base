<?php

declare(strict_types=1);


namespace FriendsOfTYPO3\CrowdinBase\Tests\Configuration;

use FriendsOfTYPO3\CrowdinBase\Configuration\ConfigurationWriter;
use FriendsOfTYPO3\CrowdinBase\Configuration\Entity\Language;
use FriendsOfTYPO3\CrowdinBase\Configuration\Entity\Project;
use FriendsOfTYPO3\CrowdinBase\Configuration\Exception\ConfigurationFileWriteException;
use FriendsOfTYPO3\CrowdinBase\Configuration\Exception\InvalidConfigurationDataException;
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
            new Project(
                1,
                'typo3-extension-some-identifier',
                'news',
                [new Language('de', 'German')]
            ),
            new Project(
                2,
                'typo3-extension-another-identifier',
                'typo3 extension tt_address',
                [new Language('fr', 'French'), new Language('it', 'Italian')]
            ),
        ];
        $subject = new ConfigurationWriter(new ExtensionKeyGenerator(), $configurationFile, []);

        $subject->write($projects);

        self::assertJsonFileEqualsJsonFile(__DIR__ . '/Output/configuration.json', $configurationFile);

        unlink($configurationFile);
    }

    #[Test]
    public function writeCreatesConfigurationFileWithoutGivenSkippedProjectsCorrectly(): void
    {
        $configurationFile = '/tmp/crowdin-base-configuration-writer-test/' . uniqid();
        $projects = [
            new Project(
                1,
                'typo3-extension-some-identifier',
                'news',
                [new Language('de', 'German')]
            ),
            new Project(
                2,
                'typo3-extension-another-identifier',
                'typo3 extension tt_address',
                [new Language('fr', 'French'), new Language('it', 'Italian')]
            ),
        ];
        $subject = new ConfigurationWriter(new ExtensionKeyGenerator(), $configurationFile, ['typo3-extension-some-identifier']);

        $subject->write($projects);

        self::assertJsonFileEqualsJsonFile(__DIR__ . '/Output/configuration-without-skipped.json', $configurationFile);

        unlink($configurationFile);
    }

    #[Test]
    public function writeThrowsErrorOnInvalidJson(): void
    {
        $this->expectException(InvalidConfigurationDataException::class);

        $configurationFile = '/tmp/crowdin-base-configuration-writer-test/' . uniqid();
        $projects = [
            new Project(
                1,
                'typo3-extension-some-identifier',
                "typo3 extension news\xB1\x31",
                [new Language('de', 'German')]
            ),
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
            new Project(
                1,
                'typo3-extension-some-identifier',
                'news',
                [new Language('de', 'German')]
            ),
        ];

        $subject = new ConfigurationWriter(new ExtensionKeyGenerator(), $configurationFile, []);

        $subject->write($projects);
    }
}
