<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\CrowdinBase\Console\Output;

use Symfony\Component\Console\Style\SymfonyStyle;

readonly class ProjectOutput implements OutputInterface
{
    public function __construct(
        private SymfonyStyle $io
    ) {}

    public function start(): void
    {
        $this->io->writeln('Processing projects ...');
    }

    public function advance(string $text): void
    {
        static $count = 0;

        $this->io->writeln(\sprintf('%d. %s', ++$count, $text));
    }

    public function finish(array $notes, array $errors): void
    {
        foreach ($notes as $notice) {
            $this->io->note($notice);
        }

        foreach ($errors as $error) {
            $this->io->error($error);
        }
    }
}
