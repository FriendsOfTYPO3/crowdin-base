<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\CrowdinBase\Settings;

/**
 * Provides the settings, mostly taken from .env file.
 * @internal
 */
final readonly class Settings
{
    /**
     * @param list<non-empty-string> $skippedProjects
     */
    private function __construct(
        public string $configurationFile,
        public string $crowdinAccessToken,
        public array $skippedProjects,
    ) {}

    /**
     * @param array<string, string> $env
     * @param list<non-empty-string> $skippedProjects
     */
    public static function fromEnvironment(array $env, array $skippedProjects, PathResolver $pathResolver): self
    {
        foreach (EnvironmentVariables::cases() as $variable) {
            if (!isset($env[$variable->name])) {
                throw UndefinedEnvironmentVariableException::fromEnvironmentKey($variable->name);
            }
        }

        $projectPath = $pathResolver->getProjectPath(__DIR__);

        return new self(
            $projectPath . '/' . $env['CONFIGURATION_FILE'],
            $env['CROWDIN_ACCESS_TOKEN'],
            $skippedProjects,
        );
    }
}
