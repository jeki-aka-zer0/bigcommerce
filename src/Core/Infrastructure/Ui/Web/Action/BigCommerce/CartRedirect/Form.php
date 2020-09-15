<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\Action\BigCommerce\CartRedirect;

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

    public function __construct(ServerRequestInterface $request)
    {
        $queryParams = $request->getQueryParams();

        $this->cartId = $queryParams['cart_id'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'cart_id' => $this->cartId,
        ];
    }
}
