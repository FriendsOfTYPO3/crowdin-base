<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\CrowdinBase\Settings;

/**
 * Provides the settings, mostly taken from .env file.
 * @internal
 */
final readonly class Settings
{
    private const array REQUIRED_ENV_KEYS = [
        'CONFIGURATION_FILE',
        'CROWDIN_ACCESS_TOKEN',
    ];

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
        foreach (self::REQUIRED_ENV_KEYS as $key) {
            if (!isset($env[$key])) {
                throw UndefinedEnvironmentVariableException::fromEnvironmentKey($key);
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
