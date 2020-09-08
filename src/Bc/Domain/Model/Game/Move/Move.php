<?php

declare(strict_types=1);

namespace Src\Bc\Domain\Model\Game\Move;

use DateTimeImmutable;
use Src\Bc\Domain\Model\Id;
use Doctrine\ORM\Mapping as ORM;
use Src\Bc\Domain\Model\Game\Game;
use Src\Bc\Domain\Model\Game\Figures;

/**
 * @ORM\Entity
 * @ORM\Table(name="moves")
 */
final class Move
{
    /**
     * @var Id
     * @ORM\Column(type="id")
     * @ORM\Id
     */
    private Id $id;

    /**
     * @var Game
     * @ORM\ManyToOne(targetEntity="Src\Bc\Domain\Model\Game\Game")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private Game $game;

    /**
     * @var Figures
     * @ORM\Column(type="figures")
     */
    private Figures $figures;

    /**
     * @var int
     * @ORM\Column(type="smallint", name="bulls")
     */
    private int $bulls;

    /**
     * @var int
     * @ORM\Column(type="smallint", name="cows")
     */
    private int $cows;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="create_at")
     */
    private DateTimeImmutable $createdAt;

    public function __construct(Id $id, Game $game, Figures $figures)
    {
        $this->id = $id;
        $this->game = $game;
        $this->figures = $figures;
        $result = $game->getFigures()->compare($figures);
        $this->bulls = $result->getBulls();
        $this->cows = $result->getCows();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getFigures(): Figures
    {
        return $this->figures;
    }

    public function getBulls(): int
    {
        return $this->bulls;
    }

    public function getCows(): int
    {
        return $this->cows;
    }
}
