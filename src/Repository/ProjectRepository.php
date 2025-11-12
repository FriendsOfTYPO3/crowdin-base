<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\CrowdinBase\Repository;

use CrowdinApiClient\Crowdin;
use FriendsOfTYPO3\CrowdinBase\Configuration\Entity\Project;

/**
 * Communicates with the Crowdin API for the project resource.
 * @internal
 */
final readonly class ProjectRepository
{
    private const int LIMIT = 500;

    public function __construct(
        private Crowdin $crowdin,
    ) {}

    /**
     * @return list<Project>
     */
    public function findAll(): array
    {
        /** @var iterable<\CrowdinApiClient\Model\Project> $items */
        $items = $this->crowdin->project->list(['limit' => self::LIMIT]); // @todo implement pagination

        $projects = [];
        foreach ($items as $item) {
            /** @var list<non-empty-string> $languageIds */
            $languageIds = $item->getTargetLanguageIds();
            \sort($languageIds);
            $projects[] = new Project(
                $item->getId(),
                $item->getIdentifier(),
                $item->getName(),
                $languageIds,
            );
        }

        return $projects;
    }
}
