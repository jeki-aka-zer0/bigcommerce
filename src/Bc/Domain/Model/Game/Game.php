<?php

declare(strict_types=1);

namespace Src\Bc\Domain\Model\Game;

use DateTimeImmutable;
use Src\Bc\Domain\Model\Id;
use Doctrine\ORM\Mapping as ORM;
use Src\Bc\Domain\Model\Player\Player;

/**
 * @ORM\Entity
 * @ORM\Table(name="games")
 */
final class Game
{
    /**
     * @var Id
     * @ORM\Column(type="id")
     * @ORM\Id
     */
    private Id $id;

    /**
     * @var Player
     * @ORM\ManyToOne(targetEntity="Src\Bc\Domain\Model\Player\Player")
     * @ORM\JoinColumn(name="player_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private Player $player;

    /**
     * @var Level
     * @ORM\Column(type="level")
     */
    private Level $level;

    /**
     * @var Figures
     * @ORM\Column(type="figures")
     */
    private Figures $figures;

    /**
     * @var bool|null
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $result;

    /**
     * @var int
     * @ORM\Column(type="smallint", name="moves_count")
     */
    private int $movesCount;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="create_at")
     */
    private DateTimeImmutable $createdAt;

    public function __construct(Id $id, Player $player, Level $level, Figures $figures)
    {
        $this->id = $id;
        $this->player = $player;
        $this->level = $level;
        $this->figures = $figures;
        $this->result = null;
        $this->movesCount = 0;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getLevel(): Level
    {
        return $this->level;
    }

    public function getFigures(): Figures
    {
        return $this->figures;
    }

    public function getResult(): ?bool
    {
        return $this->result;
    }

    public function getMovesCount(): int
    {
        return $this->movesCount;
    }

    public function getRemainingNumberOfMoves(RulesDto $rules): ?int
    {
        return $this->getLevel()->isHard()
            ? $rules->getMaxMovesCountForHardLevel() - $this->getMovesCount()
            : null;
    }

    public function isMovesLimitReached(RulesDto $rules): bool
    {
        return $this->getLevel()->isHard() && $this->getMovesCount() >= $rules->getMaxMovesCountForHardLevel();
    }

    public function finishMove(bool $isVictory): void
    {
        $this->move();
        if ($isVictory) {
            $this->result = $isVictory;
        }
    }

    public function move(): void
    {
        $this->movesCount++;
    }

    public function loose(): void
    {
        $this->result = false;
    }
}
