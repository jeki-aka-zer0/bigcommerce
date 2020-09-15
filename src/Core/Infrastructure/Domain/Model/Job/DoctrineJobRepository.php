<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Domain\Model\Job;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Src\Core\Domain\Model\Job\Job;
use Src\Core\Domain\Model\Job\JobRepository;
use Src\Core\Domain\Model\Job\Sign;

final class DoctrineJobRepository implements JobRepository
{
    private EntityManagerInterface $em;

    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Job::class);
    }

    public function findBySign(Sign $sign): ?Job
    {
        /** @var Job|null $job */
        $job = $this->repo->findOneBy(['sign' => $sign->getSign()]);

        return $job;
    }

    public function findAllBySign(Sign $sign): array
    {
        return $this->repo->findBy(['sign' => $sign->getSign()]);
    }

    public function pop(): array
    {
        $sql = <<<SQL
            DELETE FROM jobs j
            WHERE j.ctid IN (SELECT ctid FROM jobs 
                WHERE scheduled_at <= now() ORDER BY scheduled_at LIMIT 1000)
            RETURNING *
        SQL;

        $rsm = new ResultSetMappingBuilder($this->em);
        $rsm->addRootEntityFromClassMetadata(Job::class, 'j');

        return $this->em->createNativeQuery($sql, $rsm)->getResult();
    }

    public function add(Job $job): void
    {
        $this->em->persist($job);
    }

    public function removeAllBySign(Sign $sign): void
    {
        array_map(fn(Job $job) => $this->em->remove($job), $this->findAllBySign($sign));
    }
}
