<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\CrowdinBase\Configuration\Exception;

final class InvalidConfigurationFileException extends \RuntimeException
{
    public static function fromInvalidJson(string $configurationFile, \JsonException $previous): self
    {
        return new self(
            sprintf('Configuration file "%s" is not valid JSON. Run the crowdin:setup command again.', $configurationFile),
            1762870304,
            $previous,
        );
    }

    public static function fromMissingProjectsKey(string $configurationFile): self
    {
        return new self(
            sprintf('Configuration file "%s" misses the projects key. Run the crowdin:setup command again.', $configurationFile),
            1762870305,
        );
    }
}
