<?php

namespace App\Command;

use App\Entity\User;
use App\Service\FetchRemoteData;
use App\Service\SavePosts;
use App\Service\SaveUsers;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(
    name: 'save-remote-posts',
    description: 'Fetches user posts from a remote api and saves them to a database',
)]
class SaveRemotePostsCommand extends Command
{
    public function __construct(
        private readonly SavePosts $savePosts,
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

        try {
            $numberOfPostsInserted = ($this->savePosts)();
        } catch (
            ClientExceptionInterface |
            DecodingExceptionInterface |
            RedirectionExceptionInterface |
            ServerExceptionInterface |
            TransportExceptionInterface) {
            $io->error('Something went wrong while importing the data');

            return Command::FAILURE;
        }

        $io->success(\sprintf('%d posts imported into the database successfully!', $numberOfPostsInserted));

        return Command::SUCCESS;
    }
}
