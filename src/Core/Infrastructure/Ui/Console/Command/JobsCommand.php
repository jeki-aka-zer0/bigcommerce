<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Console\Command;

use Psr\Log\LoggerInterface;
use Src\Core\Domain\Model\Job\JobProcessor;
use Src\Core\Domain\Model\Job\JobRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use function count;

final class JobsCommand extends Command
{
    private JobRepository $jobs;

    private JobProcessor $processor;

    private LoggerInterface $logger;

    public function __construct(JobRepository $jobs, JobProcessor $processor, LoggerInterface $logger, string $name = null)
    {
        parent::__construct($name);
        $this->jobs = $jobs;
        $this->processor = $processor;
        $this->logger = $logger;
    }

    protected function configure(): void
    {
        $this->setName('jobs:run');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        while (true) {
            $jobs = $this->jobs->pop();

            if (0 === count($jobs)) {
                echo $output->writeln('<comment>Waiting...</comment>');

                sleep(1);

                continue;
            }

            foreach ($jobs as $job) {
                try {
                    $this->processor->process($job);
                } catch (Throwable $e) {
                    $message = sprintf('Job failed: %s', $e->getMessage());
                    $this->logger->error($message, (array)$job);
                    $output->writeln(sprintf('<info>%s</info>', $message));
                }

                $output->writeln(sprintf('<info>Job processed: %s</info>', $job->getSign()->getSign()));
            }
        }
    }
}
