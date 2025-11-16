<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\CrowdinBase\Configuration\Entity;

/**
 * Entity for a project
 */
final readonly class Project
{
    /**
     * @param int $id The Crowdin ID, for example: 368353
     * @param string $identifier The Crowdin identifier, for example: "typo3-extension-news"
     * @param string $name The TYPO3 extension key, for example: "news"
     * @param list<Language> $languages
     */
    public function __construct(
        public int $id,
        public string $identifier,
        public string $name,
        public array $languages,
    ) {}
}
