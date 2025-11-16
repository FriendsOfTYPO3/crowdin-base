<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\CrowdinBase\Configuration\Entity;

/**
 * Entity for a language
 */
final readonly class Language
{
    /**
     * @param non-empty-string $id The id, for example: "de"
     * @param non-empty-string $name The name, for example: "German"
     */
    public function __construct(
        public string $id,
        public string $name,
    ) {}
}
