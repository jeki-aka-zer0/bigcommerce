<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\Action\BigCommerce\Uninstall;

use Psr\Http\Message\ServerRequestInterface;
use Src\Core\Infrastructure\Ui\Web\Action\FormInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class Form implements FormInterface
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $signedPayload;

    public function __construct(ServerRequestInterface $request)
    {
        $queryParams = $request->getQueryParams();

        $this->signedPayload = $queryParams['signed_payload'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'signed_payload' => $this->signedPayload,
        ];
    }
}
