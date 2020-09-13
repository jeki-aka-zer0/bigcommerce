<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Job;

interface JobRepository
{
    /**
     * @param Sign $sign
     *
     * @return Job[]
     */
    public function getAllBySign(Sign $sign): array;

    /**
     * @return Job[]
     */
    public function pop(): array;

    public function add(Job $job): void;
}
