<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\CrowdinBase\Configuration\Exception;

final class InvalidConfigurationDataException extends \RuntimeException
{
    public static function fromInvalidJson(\JsonException $previous): self
    {
        return new self(
            'Configuration data is not valid JSON. Run the crowdin:setup command again.',
            1762889419,
            $previous,
        );
    }
}
