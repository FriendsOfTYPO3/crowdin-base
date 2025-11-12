<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\CrowdinBase\Configuration\Exception;

final class UnavailableConfigurationFileException extends \RuntimeException
{
    public static function fromFileDoesNotExist(string $configurationFile): self
    {
        return new self(
            sprintf('Configuration file "%s" does not exist. Make sure you run the crowdin:setup command.', $configurationFile),
            1762870302
        );
    }

    public static function fromFileIsNotReadable(string $configurationFile): self
    {
        return new self(
            sprintf('Configuration file "%s" is not readable.', $configurationFile),
            1762870303
        );
    }
}
