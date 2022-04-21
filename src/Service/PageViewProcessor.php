<?php

declare(strict_types=1);

namespace App\Service;

use App\Contract\Service\ClockInterface;
use App\Contract\Service\Parser\ParserResultProcessorInterface;
use App\Contract\Service\Parser\ParserSelectorInterface;
use App\Doctrine\Entity\PageView;
use App\DTO\ParserResult;
use App\Service\Repository\PageViewRepository;

final class PageViewProcessor
{
    public function __construct(
        private ClockInterface $clock,
        private PageViewRepository $pageViewRepository,
        private ParserSelectorInterface $parserSelector,
        private ParserResultProcessorInterface $parserResultManager,
    ) {
    }

    public function process(int $batchSize): void
    {
        $records = $this->pageViewRepository->getUnparsed(batchSize: $batchSize);

        /** @var PageView $pageView */
        foreach ($records as $pageView) {
            $parser = $this->parserSelector->findParser($pageView);

            if (null !== $parser) {
                $parserResult = $parser->parse(pageView: $pageView);
            } else {
                $parserResult = new ParserResult();
                $parserResult->setErrors(errors: ['Could not find parser that supports this page view']);
            }

            if (false === $parserResult->hasErrors()) {
                $this->parserResultManager->persist(parserResult: $parserResult);
            }

            $currentDateTime = $this->clock->getCurrentDateTime();
            $this->pageViewRepository->saveAsParsed(
                pageView: $pageView,
                parsedAt: $currentDateTime,
                errors: $parserResult->getErrors(),
            );
        }
    }
}
