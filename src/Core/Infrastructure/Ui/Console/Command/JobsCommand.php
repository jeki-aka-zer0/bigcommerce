<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Console\Command;

use Src\Core\Domain\Model\Job\JobProcessor;
use Src\Core\Domain\Model\Job\JobRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class JobsCommand extends Command
{
    private JobRepository $jobs;

    private JobProcessor $processor;

    public function __construct(JobRepository $jobs, JobProcessor $processor, string $name = null)
    {
        parent::__construct($name);
        $this->jobs = $jobs;
        $this->processor = $processor;
    }

    protected function configure(): void
    {
        $this->setName('jobs:run');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        while (true) {
            $jobs = $this->jobs->pop();

            if (0 === \count($jobs)) {
                echo $output->writeln('<comment>Waiting...</comment>');

                sleep(1);

                continue;
            }

            foreach ($jobs as $job) {
                $this->processor->process($job);

                $output->writeln(sprintf('<info>Job processed: %s</info>', $job->getSign()->getSign()));
            }
        }
    }
}
