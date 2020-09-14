<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Job;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Src\Core\Domain\Model\Auth\Integration;
use Src\Core\Domain\Model\Id;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity
 * @ORM\Table(name="jobs", indexes={
 *     @ORM\Index(name="jobs_scheduled_at_idx", columns={"scheduled_at"}),
 * }, uniqueConstraints={
 *     @UniqueConstraint(name="sign_uidx", columns={"sign"})
 * })
 */
final class Job
{
    /**
     * @var Id
     * @ORM\Column(type="id")
     * @ORM\Id
     */
    private Id $id;

    /**
     * @var Integration
     * @ORM\ManyToOne(targetEntity="Src\Core\Domain\Model\Auth\Integration")
     * @ORM\JoinColumn(name="integration_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private Integration $integration;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $subscriberId;

    /**
     * @var Sign
     * @ORM\Column(type="sign")
     */
    private Sign $sign;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="scheduled_at")
     */
    private DateTimeImmutable $scheduledAt;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="create_at")
     */
    private DateTimeImmutable $createdAt;

    public function __construct(Id $id, Sign $sign, Integration $integration, DateTimeImmutable $scheduledAt, ?int $subscriberId)
    {
        $this->id = $id;
        $this->sign = $sign;
        $this->integration = $integration;
        $this->scheduledAt = $scheduledAt;
        $this->subscriberId = $subscriberId;
        $this->createdAt = new DateTimeImmutable();
    }

    public function reschedule(DateTimeImmutable $scheduledAt): void
    {
        $this->scheduledAt = $scheduledAt;
    }

    public function getSign(): Sign
    {
        return $this->sign;
    }

    public function getIntegration(): Integration
    {
        return $this->integration;
    }

    public function setSubscriberId(int $subscriberId): void
    {
        $this->subscriberId = $subscriberId;
    }
}
