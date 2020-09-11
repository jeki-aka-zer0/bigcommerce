<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Cart;

use Src\Core\Domain\Model\Webhook\AbstractWebhookProcessor;

final class CartUpdatedWebhookProcessor extends AbstractWebhookProcessor
{
    public function process(): void
    {
    }
}
