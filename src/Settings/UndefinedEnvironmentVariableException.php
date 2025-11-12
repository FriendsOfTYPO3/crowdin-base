<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\CrowdinBase\Settings;

final class UndefinedEnvironmentVariableException extends \DomainException
{
    public static function fromEnvironmentKey(string $key): self
    {
        return new self(
            \sprintf(
                'The environment variable "%s" is not defined in the .env file. Have a look into the .env.example file for more details.',
                $key,
            ),
            1762853210
        );
    }
}
