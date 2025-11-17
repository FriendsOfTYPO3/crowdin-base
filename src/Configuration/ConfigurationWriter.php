<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\CrowdinBase\Configuration;

use FriendsOfTYPO3\CrowdinBase\Configuration\Entity\Project;
use FriendsOfTYPO3\CrowdinBase\Configuration\Exception\ConfigurationFileWriteException;
use FriendsOfTYPO3\CrowdinBase\Configuration\Exception\InvalidConfigurationDataException;
use FriendsOfTYPO3\CrowdinBase\Extension\ExtensionKeyGenerator;

/**
 * Writes the configuration to the configured configuration file.
 * @internal
 */
final readonly class ConfigurationWriter
{
    /**
     * @param list<non-empty-string> $skippedProjects
     */
    public function __construct(
        private ExtensionKeyGenerator $extensionKeyGenerator,
        private string $configurationFile,
        private array $skippedProjects
    ) {}

    /**
     * @param list<Project> $projects
     */
    public function write(array $projects): void
    {
        usort($projects, static fn(Project $a, Project $b): int => $a->identifier <=> $b->identifier);
        $projectsAsArray = [];
        foreach ($projects as $project) {
            if (in_array($project->identifier, $this->skippedProjects, true)) {
                continue;
            }

            $projectsAsArray[$project->identifier] = [
                'id' => $project->id,
                'extensionKey' => $this->extensionKeyGenerator->generate($project->identifier, $project->extensionKey),
                'languages' => $project->languages,
            ];
        }

        try {
            $configurationAsJson = json_encode([
                'projects' => $projectsAsArray,
            ], \JSON_PRETTY_PRINT | \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw InvalidConfigurationDataException::fromInvalidJson($e);
        }

        $targetDirectory = dirname($this->configurationFile);
        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0750, true);
        }

        if (@file_put_contents($this->configurationFile, $configurationAsJson) === false) {
            throw ConfigurationFileWriteException::fromConfigurationFile($this->configurationFile);
        }
    }
}
