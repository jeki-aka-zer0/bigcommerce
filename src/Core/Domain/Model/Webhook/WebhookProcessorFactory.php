<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Webhook;

use Src\Core\Domain\Model\Cart\CartWebhookProcessor;

final class WebhookProcessorFactory
{
    private CartWebhookProcessor $cartProcessor;

    public function __construct(CartWebhookProcessor $cartProcessor)
    {
        $this->cartProcessor = $cartProcessor;
    }

    public function build(string $scope): WebhookProcessor
    {
        switch ($scope) {
            case Scopes::CART_CREATED:
            case Scopes::CART_UPDATED:
                return $this->cartProcessor;
        }

        throw new UnknownScopeException($scope);
    }
}
