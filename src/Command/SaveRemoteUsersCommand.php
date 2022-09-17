<?php

namespace App\Command;

use App\Service\SaveUsers;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'save-remote-users',
    description: 'Fetches users from a remote api and saves them to a database',
)]
class SaveRemoteUsersCommand extends Command
{
    public function __construct(
        private readonly SaveUsers $saveUsers,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $numberOfUsersInserted = ($this->saveUsers)();

        if (0 === $numberOfUsersInserted) {
            $io->note('No user was imported!');
        } else {
            $io->success(
                \sprintf('%d users imported into the database successfully!', $numberOfUsersInserted)
            );
        }

        return Command::SUCCESS;
    }
}
