<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\CrowdinBase\Configuration;

use FriendsOfTYPO3\CrowdinBase\Configuration\Entity\Project;
use FriendsOfTYPO3\CrowdinBase\Configuration\Exception\InvalidConfigurationFileException;
use FriendsOfTYPO3\CrowdinBase\Configuration\Exception\UnavailableConfigurationFileException;

/**
 * Reads the configuration from the configured configuration file.
 */
final readonly class ConfigurationReader
{
    public function __construct(
        private string $configurationFile
    ) {}

    /**
     * @return list<Project>
     */
    public function read(): array
    {
        if (!is_file($this->configurationFile)) {
            throw UnavailableConfigurationFileException::fromFileDoesNotExist($this->configurationFile);
        }
        if (!is_readable($this->configurationFile)) {
            throw UnavailableConfigurationFileException::fromFileIsNotReadable($this->configurationFile);
        }

        $configurationAsJson = file_get_contents($this->configurationFile);
        if ($configurationAsJson === false) {
            throw UnavailableConfigurationFileException::fromFileIsNotReadable($this->configurationFile);
        }
        try {
            /**
             * @var array{
             *     projects: array<non-empty-string, array{
             *         id: int,
             *         extensionKey: non-empty-string,
             *         languages: list<non-empty-string>,
             *     }>
             * } $configuration
             */
            $configuration = json_decode($configurationAsJson, true, flags: \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw InvalidConfigurationFileException::fromInvalidJson($this->configurationFile, $e);
        }

        if (!isset($configuration['projects'])) {
            throw InvalidConfigurationFileException::fromMissingProjectsKey($this->configurationFile);
        }

        $projects = [];
        foreach ($configuration['projects'] as $identifier => $project) {
            $projects[] = new Project(
                $project['id'],
                $identifier,
                $project['extensionKey'],
                $project['languages'],
            );
        }

        return $projects;
    }
}
