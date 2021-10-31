<?php

declare(strict_types=1);

namespace App\Command;

use App\Contract\UserRoles;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserManager;
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
    name: 'app:user:create',
    description: 'Creates a new user',
)]
final class UserCreateCommand extends Command
{
    private const ARGUMENT_NAME_USERNAME = 'username';
    private const ARGUMENT_NAME_ROLE = 'role';

    private ?string $username = null;
    private ?string $plaintextPassword = null;
    private array $roles = [];

    public function __construct(
        private UserRepository $userRepository,
        private UserManager $userManager,
        private SerializerInterface $serializer,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            self::ARGUMENT_NAME_USERNAME,
            null,
            InputOption::VALUE_REQUIRED,
            'Username',
            null
        );
        $this->addOption(
            self::ARGUMENT_NAME_ROLE,
            null,
            InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            sprintf('Additional roles (%s is always given)', User::DEFAULT_ROLE),
            []
        );
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $this->username = $input->getOption(self::ARGUMENT_NAME_USERNAME);

        while (true) {
            if (null === $this->username) {
                $this->askForUsername($input, $output);
            }
            if (null !== $this->userRepository->findByUsername($this->username)) {
                $io->error(sprintf('User with username=%s already exists!', $this->username));
                $this->username = null;
            } else {
                break;
            }
        }

        $this->askForPassword($input, $output);

        $this->roles = $input->getOption(self::ARGUMENT_NAME_ROLE);
        $this->addRole(User::DEFAULT_ROLE);
        $this->askForAdditionalRoles($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info(sprintf('Selected username: %s', $this->serializer->serialize($this->username, 'json')));
        $io->info(sprintf('Selected password: %s', $this->serializer->serialize($this->plaintextPassword, 'json')));
        $io->info(sprintf('Selected roles: %s', $this->serializer->serialize($this->roles, 'json')));

        $user = $this->userManager->create($this->username, $this->plaintextPassword, $this->roles);

        $io->info(sprintf('Created user: %s', $this->serializer->serialize($user, 'json')));

        return Command::SUCCESS;
    }

    private function askForUsername(InputInterface $input, OutputInterface $output): void
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $question = new Question('Username?');
        do {
            $this->username = $helper->ask($input, $output, $question);
        } while (null === $this->username);
    }

    private function askForPassword(InputInterface $input, OutputInterface $output): void
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $question = new Question('Password?');
        do {
            $this->plaintextPassword = $helper->ask($input, $output, $question);
        } while (null === $this->plaintextPassword);
    }

    private function addRole(string $role): void
    {
        $this->roles[] = $role;
        $this->roles = UserRoles::normalize($this->roles);
    }

    private function askForAdditionalRoles(InputInterface $input, OutputInterface $output): void
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $question = new Question('Additional role? (empty to finish adding)');

        $io = new SymfonyStyle($input, $output);
        while (true) {
            $io->info(sprintf('Selected roles: %s', $this->serializer->serialize($this->roles, 'json')));
            $role = $helper->ask($input, $output, $question);
            if (null === $role) {
                break;
            }
            if (UserRoles::isValidRole($role)) {
                $this->addRole($role);
            } else {
                $io->error(sprintf('Invalid role name: %s', $role));
            }
        }
    }
}
