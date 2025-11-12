<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\CrowdinBase\Extension;

/**
 * Generates the extension key out of the Crowdin name.
 * @internal
 */
final readonly class ExtensionKeyGenerator
{
    public function generate(string $identifier, string $name): string
    {
        if ($identifier === 'typo3-cms') {
            return $identifier;
        }

        if (str_starts_with($identifier, 'typo3-extension-')) {
            return trim(str_replace('typo3 extension', '', strtolower($name)));
        }

        throw UnrecognizedIdentifierException::fromInvalidIdentifier($identifier);
    }
}
