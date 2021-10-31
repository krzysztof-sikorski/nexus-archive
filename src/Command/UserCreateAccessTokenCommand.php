<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserAccessTokenManager;
use DateInterval;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerInterface;

use function sprintf;

#[AsCommand(
    name: 'app:user:create-access-token',
    description: 'Creates a new user access token',
)]
final class UserCreateAccessTokenCommand extends Command
{
    private const ARGUMENT_NAME_OWNER = 'owner';
    private const ARGUMENT_NAME_DURATION = 'duration';
    private const DEFAULT_DURATION = '1 month';

    private ?User $owner = null;
    private ?string $durationStr = null;
    private ?DateInterval $duration = null;

    public function __construct(
        private UserRepository $userRepository,
        private UserAccessTokenManager $userAccessTokenManager,
        private SerializerInterface $serializer,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            self::ARGUMENT_NAME_OWNER,
            null,
            InputOption::VALUE_REQUIRED,
            'Owner\'s username',
            null
        );

        $this->addOption(
            self::ARGUMENT_NAME_DURATION,
            null,
            InputOption::VALUE_REQUIRED,
            'Token duration (how long it is valid)',
            self::DEFAULT_DURATION
        );
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $question = new Question('Owner\'s username?', null);

        $ownerUsername = $input->getOption(self::ARGUMENT_NAME_OWNER);
        while (true) {
            if (null === $ownerUsername) {
                $ownerUsername = $helper->ask($input, $output, $question);
            }
            $this->owner = $this->userRepository->findByUsername($ownerUsername);
            if (null !== $this->owner) {
                break;
            } else {
                $io->error(sprintf('User with username=%s does not exist!', $ownerUsername));
                $ownerUsername = null;
            }
        }

        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $questionText = sprintf('Token duration (default: %s)?', self::DEFAULT_DURATION);
        $question = new Question($questionText, self::DEFAULT_DURATION);

        $this->durationStr = $input->getOption(self::ARGUMENT_NAME_DURATION);
        while (true) {
            if (null === $this->durationStr) {
                $this->durationStr = $helper->ask($input, $output, $question);
            }
            try {
                $duration = DateInterval::createFromDateString($this->durationStr);
            } catch (Exception $e) {
                $duration = null;
            }
            if (false !== $duration instanceof DateInterval) {
                $this->duration = $duration;
                break;
            } else {
                $io->error(sprintf('Invalid duration: %s', $this->durationStr));
            }
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info(
            sprintf(
                'Selected owner: %s',
                $this->serializer->serialize($this->owner, 'json')
            )
        );

        $io->info(
            sprintf(
                'Selected duration: %s (parsed as: %s)',
                $this->durationStr,
                $this->serializer->serialize($this->duration, 'json')
            )
        );

        $token = $this->userAccessTokenManager->create($this->owner, $this->duration);

        $io->info(sprintf('Created token: %s', $this->serializer->serialize($token, 'json')));

        return Command::SUCCESS;
    }
}
