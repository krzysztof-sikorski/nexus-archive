<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\UserAccessTokenManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:worker:prune-database',
    description: 'Background worker to prune unwanted content from database',
)]
final class WorkerPruneDatabaseCommand extends Command
{
    public function __construct(private UserAccessTokenManager $userAccessTokenManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->userAccessTokenManager->prune();

        return Command::SUCCESS;
    }
}
