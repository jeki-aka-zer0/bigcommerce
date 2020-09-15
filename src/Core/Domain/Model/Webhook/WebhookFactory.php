<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Webhook;

use Src\Core\Domain\Model\Cart\CartConvertedWebhookProcessor;
use Src\Core\Domain\Model\Cart\CartData;
use Src\Core\Domain\Model\Cart\CartWebhookProcessor;
use Src\Core\Domain\Model\Cart\LineItemDataAdapter;

final class WebhookFactory
{
    private CartWebhookProcessor $cartProcessor;

    private CartConvertedWebhookProcessor $cartConvertedProcessor;

    public function __construct(CartWebhookProcessor $cartProcessor, CartConvertedWebhookProcessor $cartConvertedProcessor)
    {
        $this->cartProcessor = $cartProcessor;
        $this->cartConvertedProcessor = $cartConvertedProcessor;
    }

    public function getProcessor(string $scope): WebhookProcessor
    {
        switch ($scope) {
            case Scopes::CART_CREATED:
            case Scopes::CART_UPDATED:
            case Scopes::CART_LINE_ITEM_CREATED:
            case Scopes::CART_LINE_ITEM_UPDATED:
            case Scopes::CART_LINE_ITEM_DELETED:
                return $this->cartProcessor;
            case Scopes::CART_CONVERTED:
                return $this->cartConvertedProcessor;
        }

        throw UnknownScopeException::unknownWebhook($scope);
    }

    public function getData(string $scope, array $data): Data
    {
        switch ($scope) {
            case Scopes::CART_CREATED:
            case Scopes::CART_UPDATED:
            case Scopes::CART_CONVERTED:
                return new CartData($data);
            case Scopes::CART_LINE_ITEM_CREATED:
            case Scopes::CART_LINE_ITEM_UPDATED:
            case Scopes::CART_LINE_ITEM_DELETED:
                return LineItemDataAdapter::getCartData($data);
        }

        throw UnknownScopeException::unknownWebhook($scope);
    }
}
