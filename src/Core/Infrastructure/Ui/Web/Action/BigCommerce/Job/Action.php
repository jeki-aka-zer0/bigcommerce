<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\Action\BigCommerce\Job;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Src\Core\Domain\Model\Job\JobProcessor;
use Src\Core\Domain\Model\Job\JobRepository;
use Throwable;

final class Action implements RequestHandlerInterface
{
    private JobRepository $jobs;

    private JobProcessor $processor;

    private LoggerInterface $logger;

    public function __construct(JobRepository $jobs, JobProcessor $processor, LoggerInterface $logger)
    {
        $this->jobs = $jobs;
        $this->processor = $processor;
        $this->logger = $logger;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $jobs = $this->jobs->pop();

        $errors = [];
        foreach ($jobs as $job) {
            try {
                $this->processor->process($job);
            } catch (Throwable $e) {
                $message = sprintf('Job failed: %s', $e->getMessage());
                $this->logger->error($message, (array)$job);
                $errors[] = $message;
            }
        }

        return new JsonResponse(
            [
                'processed' => \count($jobs),
                'errors' => $errors,
            ]
        );
    }
}
