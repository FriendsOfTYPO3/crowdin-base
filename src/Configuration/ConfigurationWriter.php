<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\CrowdinBase\Configuration;

use FriendsOfTYPO3\CrowdinBase\Configuration\Entity\Language;
use FriendsOfTYPO3\CrowdinBase\Configuration\Entity\Project;
use FriendsOfTYPO3\CrowdinBase\Configuration\Exception\ConfigurationFileWriteException;
use FriendsOfTYPO3\CrowdinBase\Configuration\Exception\InvalidConfigurationDataException;
use FriendsOfTYPO3\CrowdinBase\Console\Output\OutputInterface;
use FriendsOfTYPO3\CrowdinBase\Extension\ExtensionKeyGenerator;
use FriendsOfTYPO3\CrowdinBase\Language\SpecialLanguage;

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
    public function write(array $projects, OutputInterface $output): void
    {
        $output->start();

        usort($projects, static fn(Project $a, Project $b): int => $a->identifier <=> $b->identifier);
        $projectsAsArray = [];
        $notes = [];
        $warnings = [];
        foreach ($projects as $project) {
            $output->advance(\sprintf('Project "%s"', $project->extensionKey));

            if (in_array($project->identifier, $this->skippedProjects, true)) {
                $notes[] = \sprintf('Project "%s" is skipped', $project->extensionKey);
                continue;
            }

            $languages = $project->languages;
            $inContextLanguage = array_find($languages, static fn(Language $language): bool => $language->id === SpecialLanguage::IN_CONTEXT->value);
            if ($inContextLanguage !== null) {
                $warnings[] = \sprintf('Project "%s" has in-context language configured', $project->extensionKey);
                $languages = array_filter($languages, static fn(Language $language): bool => $language->id !== SpecialLanguage::IN_CONTEXT->value);
            }

            $projectsAsArray[$project->identifier] = [
                'id' => $project->id,
                'extensionKey' => $this->extensionKeyGenerator->generate($project->identifier, $project->extensionKey),
                'languages' => $languages,
            ];
        }

        $output->finish($notes, $warnings);

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
