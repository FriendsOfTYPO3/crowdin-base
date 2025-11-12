<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\CrowdinBase\Extension;

final class UnrecognizedIdentifierException extends \DomainException
{
    public static function fromInvalidIdentifier(string $identifier): self
    {
        return new self(
            sprintf('Identifier "%s" not allowed!', $identifier),
            1762860658
        );
    }
}
