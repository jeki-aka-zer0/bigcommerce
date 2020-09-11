<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Webhook;

use Src\Core\Domain\Model\Cart\CartCreatedWebhookProcessor;

final class WebhookProcessorFactory
{
    public static function build(string $scope, array $data): WebhookProcessor
    {
        switch ($scope) {
            case Scopes::CART_CREATED:
                return new CartCreatedWebhookProcessor($scope, $data);
            case Scopes::CART_UPDATED:
                return new CartCreatedWebhookProcessor($scope, $data);
        }

        throw new UnknownScopeException($scope);
    }
}
