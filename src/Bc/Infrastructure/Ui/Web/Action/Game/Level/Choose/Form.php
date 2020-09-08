<?php

declare(strict_types=1);

namespace Src\Bc\Infrastructure\Ui\Web\Action\Game\Level\Choose;

use Psr\Http\Message\ServerRequestInterface;
use Src\Bc\Infrastructure\Ui\Web\Action\FormInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class Form implements FormInterface
{
    /**
     * @var int
     * @Assert\NotBlank(message="id.required")
     * @Assert\Positive(message="id.required")
     */
    public $id;

    /**
     * @var string
     * @Assert\NotBlank(message="level.required")
     * @Assert\Type("string", message="level.string")
     */
    public $level;

    public function __construct(ServerRequestInterface $request)
    {
        $this->id = (int)($request->getParsedBody()['id'] ?? 0);
        $this->level = $request->getParsedBody()['level'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'subscriberId' => $this->id,
            'level' => $this->level,
        ];
    }
}
