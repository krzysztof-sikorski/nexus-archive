<?php

declare(strict_types=1);

namespace App\Command;

use App\Doctrine\Entity\Nexus\GamePeriod;
use App\Doctrine\Entity\Nexus\Leaderboard;
use App\DTO\Nexus\Leaderboard\Entry;
use App\Service\Repository\Nexus\GamePeriodRepository;
use App\Service\Repository\Nexus\LeaderboardRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Serializer\SerializerInterface;

use function intval;
use function is_numeric;
use function max;
use function printf;
use function sprintf;
use function str_pad;
use function strlen;
use function ucfirst;

use const PHP_EOL;

#[AsCommand(
    name: 'app:export:leaderboards',
    description: 'Export leaderboards into a file',
)]
final class ExportLeaderboardsCommand extends BaseCommand
{
    private const OPTION_NAME_GAME_PERIOD_ID = 'period';
    private const TABLE_HEADER_CHARACTER = 'Character';

    private ?GamePeriod $gamePeriod = null;

    public function __construct(
        private GamePeriodRepository $gamePeriodRepository,
        private LeaderboardRepository $leaderboardRepository,
        SerializerInterface $serializer,
    ) {
        parent::__construct(serializer: $serializer);
    }

    protected function configure(): void
    {
        $this->addOption(
            name: self::OPTION_NAME_GAME_PERIOD_ID,
            mode: InputOption::VALUE_REQUIRED,
            description: 'Game period ID',
        );
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $io = $this->createSymfonyStyle(input: $input, output: $output);
        $helper = $this->getQuestionHelper();
        $question = new Question(question: 'Input game period ID:');

        $inputStr = $input->getOption(name: self::OPTION_NAME_GAME_PERIOD_ID);
        $gamePeriodId = is_numeric($inputStr) ? intval($inputStr) : null;

        while (true) {
            if (null === $gamePeriodId) {
                do {
                    $inputStr = $helper->ask(input: $input, output: $output, question: $question);
                    $gamePeriodId = is_numeric($inputStr) ? intval($inputStr) : null;
                } while (null === $gamePeriodId);
            }
            $this->gamePeriod = $this->gamePeriodRepository->findById(id: $gamePeriodId);
            if (null === $this->gamePeriod) {
                $io->error(message: sprintf('Game period with id=%s does not exists!', $inputStr));
                $gamePeriodId = null;
            } else {
                break;
            }
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->createSymfonyStyle(input: $input, output: $output);

        $this->displayValue(io: $io, label: 'Selected game period', value: $this->gamePeriod->getName());

        $leaderboards = $this->leaderboardRepository->findByGamePeriod(gamePeriod: $this->gamePeriod);

        /** @var Leaderboard $leaderboard */
        foreach ($leaderboards as $leaderboard) {
            echo PHP_EOL, PHP_EOL, PHP_EOL;
            $category = $leaderboard->getCategory();
            $entries = $leaderboard->getEntries();
            printf(
                '[b]%s (%s)[/b]',
                $category->getName(),
                ucfirst(string: $category->getType()),
            );
            echo PHP_EOL, '[code]', PHP_EOL;
            $characterColumnWidth = strlen(string: self::TABLE_HEADER_CHARACTER);
            /** @var Entry $entry */
            foreach ($entries as $entry) {
                $characterNameLength = strlen(string: $entry->getCharacterName());
                $characterColumnWidth = max($characterColumnWidth, $characterNameLength + 4);
            }
            printf(
                '%s %s',
                str_pad(string: self::TABLE_HEADER_CHARACTER, length: $characterColumnWidth),
                $category->getScoreLabel(),
            );
            echo PHP_EOL;
            /** @var Entry $entry */
            foreach ($entries as $position => $entry) {
                $characterStr = sprintf('%d) %s', $position, $entry->getCharacterName());
                printf(
                    '%s %s',
                    str_pad(string: $characterStr, length: $characterColumnWidth),
                    $entry->getScore(),
                );
                echo PHP_EOL;
            }
            echo '[/code]', PHP_EOL;
        }

        return Command::SUCCESS;
    }
}
