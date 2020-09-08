<?php

declare(strict_types=1);

namespace Src\Bc\Infrastructure\Ui\Web\Action\Game\Move;

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
    private $id;

    /**
     * @var string
     * @Assert\NotBlank(message="figures.required")
     * @Assert\Type("string", message="figures.string")
     */
    private $figures;

    public function __construct(ServerRequestInterface $request)
    {
        $this->id = (int)($request->getParsedBody()['id'] ?? 0);
        $this->figures = $request->getParsedBody()['figures'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'subscriberId' => $this->id,
            'figures' => $this->figures,
        ];
    }
}
