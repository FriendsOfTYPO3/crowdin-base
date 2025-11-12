<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\CrowdinBase\Configuration\Entity;

/**
 * Entity for a project
 */
final readonly class Project
{
    /**
     * @param list<non-empty-string> $languages
     */
    public function __construct(
        public int $id,
        public string $identifier,
        public string $name,
        public array $languages,
    ) {}
}
