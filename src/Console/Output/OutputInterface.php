<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\CrowdinBase\Console\Output;

interface OutputInterface
{
    public function start(): void;

    public function advance(string $text): void;

    /**
     * @param list<string> $notes
     * @param list<string> $errors
     */
    public function finish(array $notes, array $errors): void;
}
