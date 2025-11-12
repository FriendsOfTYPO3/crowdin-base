<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\CrowdinBase\Configuration\Exception;

final class ConfigurationFileWriteException extends \RuntimeException
{
    public static function fromConfigurationFile(string $configurationFile): self
    {
        return new self(
            \sprintf('Configuration file "%s" cannot be written', $configurationFile),
            1762889740,
        );
    }
}
