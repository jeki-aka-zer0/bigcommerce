<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\Action\BigCommerce\Update;

use Psr\Http\Message\ServerRequestInterface;
use Src\Core\Infrastructure\Ui\Web\Action\FormInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class Form implements FormInterface
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $storeHash;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $apiKey;

    public function __construct(ServerRequestInterface $request)
    {
        $body = $request->getParsedBody();

        $this->storeHash = $body['store_hash'] ?? null;
        $this->apiKey = $body['api_key'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'store_hash' => $this->storeHash,
            'api_key' => $this->apiKey,
        ];
    }
}
