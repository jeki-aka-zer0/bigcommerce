<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Job;

use Webmozart\Assert\Assert;

final class Sign
{
    private const DELIMITER = ':';

    private string $sign;

    private string $triggerKey;

    private string $identity;

    public function __construct(string $sign)
    {
        $this->sign = trim($sign);
        $chunks = (array)(explode(self::DELIMITER, $this->sign));
        Assert::count($chunks, 2);

        $this->triggerKey = $chunks[0];
        $this->identity = $chunks[1];
    }

    public static function build(string $triggerKey, string $identity): self
    {
        return new self($triggerKey . self::DELIMITER . $identity);
    }

    public static function fromJob(Job $job): self
    {
        return new self($job->getSign());
    }

    public function getIdentity(): string
    {
        return $this->identity;
    }

    public function getTriggerKey(): string
    {
        return $this->triggerKey;
    }

    public function getSign(): string
    {
        return $this->sign;
    }

    public function __toString(): string
    {
        return $this->getSign();
    }
}
