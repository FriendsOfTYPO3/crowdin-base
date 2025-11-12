<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\CrowdinBase\Command;

use FriendsOfTYPO3\CrowdinBase\Configuration\ConfigurationWriter;
use FriendsOfTYPO3\CrowdinBase\Repository\ProjectRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'crowdin:setup',
    description: 'Retrieve the list of TYPO3 Crowdin projects and store it locally'
)]
final class SetupCommand extends Command
{
    public function __construct(
        private readonly ProjectRepository $repository,
        private readonly ConfigurationWriter $writer
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $projects = $this->repository->findAll();
            $this->writer->write($projects);
        } catch (\Throwable $t) {
            $io->error('An error occurred: ' . $t->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
