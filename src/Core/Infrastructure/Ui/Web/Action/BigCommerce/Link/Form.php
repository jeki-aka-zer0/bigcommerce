<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\Action\BigCommerce\Link;

use Psr\Http\Message\ServerRequestInterface;
use Src\Core\Infrastructure\Ui\Web\Action\FormInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class Form implements FormInterface
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $cartId;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $sessionId;

    /**
     * @var int
     * @Assert\NotBlank()
     */
    private $accountId;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $storeHash;

    public function __construct(ServerRequestInterface $request)
    {
        $params = $request->getQueryParams();

        $this->cartId = $params['cart_id'] ?? null;
        $this->sessionId = $params['session_id'] ?? null;
        $this->accountId = $params['account_id'] ?? null;
        $this->storeHash = $params['store_hash'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'cart_id' => $this->cartId,
            'session_id' => $this->sessionId,
            'account_id' => $this->accountId,
            'store_hash' => $this->storeHash,
        ];
    }
}
