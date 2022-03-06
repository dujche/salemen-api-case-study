<?php

declare(strict_types=1);

namespace Parser\Command;

use Parser\Service\ImportService;
use Parser\Service\ParserInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @codeCoverageIgnore
 */
class ParseCommand extends Command
{
    private ImportService $importService;
    private ParserInterface $parser;

    public function __construct(ImportService $importService, ParserInterface $parser, string $name = null)
    {
        parent::__construct($name);
        $this->importService = $importService;
        $this->parser = $parser;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        while (true) {
            sleep(5);
            $output->writeln('Looking for unprocessed files');
            $file = $this->importService->getFirstUnprocessed();

            if ($file === null) {
                $output->writeln('No new files to process');
                continue;
            }

            $this->importService->markAsProcessing($file->getId());
            $parseResult = $this->parser->parse(base64_decode($file->getContent()));
            $this->importService->markAsProcessed($file->getId(), $parseResult);
        }
    }
}
