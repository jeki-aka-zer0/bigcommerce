<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Job;

interface JobRepository
{
    public function findBySign(Sign $sign): ?Job;

    /**
     * @param Sign $sign
     *
     * @return Job[]
     */
    public function findAllBySign(Sign $sign): array;

    /**
     * @return Job[]
     */
    public function pop(): array;

    public function add(Job $job): void;

    public function removeAllBySign(Sign $sign): void;
}
