<?php

declare(strict_types=1);

namespace Src\Bc\Infrastructure\Domain\Model\Player;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Src\Bc\Domain\Model\Player\PlayerNotFoundException;
use Src\Bc\Domain\Model\Player\Player;
use Src\Bc\Domain\Model\Player\PlayerRepositoryInterface;

final class DoctrinePlayerRepository implements PlayerRepositoryInterface
{
    private EntityManagerInterface $em;

    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Player::class);
    }

    public function findBySubscriberId(int $subscriberId): ?Player
    {
        /** @var Player $player */
        $player = $this->repo->findOneBy(
            [
                'subscriberId' => $subscriberId,
            ]
        );

        return $player;
    }

    /**
     * @param int $subscriberId
     *
     * @return Player
     * @throws PlayerNotFoundException
     */
    public function getBySubscriberId(int $subscriberId): Player
    {
        $player = $this->findBySubscriberId($subscriberId);
        if (null === $player) {
            throw new PlayerNotFoundException();
        }

        return $player;
    }

    public function add(Player $player): void
    {
        $this->em->persist($player);
    }
}
