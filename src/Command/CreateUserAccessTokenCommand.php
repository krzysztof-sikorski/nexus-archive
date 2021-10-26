<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\UserAccessTokenFactory;
use DateInterval;
use ErrorException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

use function base64_encode;
use function random_bytes;
use function sprintf;

#[AsCommand(
    name: 'app:create:user-access-token',
    description: 'Creates a new user access token',
)]
final class CreateUserAccessTokenCommand extends Command
{
    private const ARGUMENT_NAME_DURATION = 'duration';
    private const DEFAULT_DURATION = '1 month';

    public function __construct(private UserAccessTokenFactory $userAccessTokenFactory)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            self::ARGUMENT_NAME_DURATION,
            null,
            InputOption::VALUE_OPTIONAL,
            'Token duration (how long it is valid)',
            self::DEFAULT_DURATION
        );
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $duration = $input->getOption(self::ARGUMENT_NAME_DURATION);
        if (null === $duration) {
            /** @var QuestionHelper $helper */
            $helper = $this->getHelper('question');
            $questionText = sprintf('Token duration (default: %s)?', self::DEFAULT_DURATION);
            $question = new Question($questionText, self::DEFAULT_DURATION);
            $duration = $helper->ask($input, $output, $question);
            $input->setOption(self::ARGUMENT_NAME_DURATION, $duration);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $durationStr = $input->getOption(self::ARGUMENT_NAME_DURATION);

        try {
            $duration = DateInterval::createFromDateString($durationStr);
        } catch (ErrorException $e) {
            $io->error(sprintf('Invalid duration: %s', $durationStr));
            return Command::FAILURE;
        }

        $io->info(sprintf('Duration: %s', var_export($duration, true)));

        $value = base64_encode(random_bytes(32));

        $token = $this->userAccessTokenFactory->create($value, $duration);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
