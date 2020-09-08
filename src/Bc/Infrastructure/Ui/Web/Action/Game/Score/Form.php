<?php

declare(strict_types=1);

namespace Src\Bc\Infrastructure\Ui\Web\Action\Game\Score;

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

    public function __construct(ServerRequestInterface $request)
    {
        $this->id = (int)($request->getQueryParams()['id'] ?? 0);
    }

    public function toArray(): array
    {
        return [
            'subscriberId' => $this->id,
        ];
    }
}
