<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Cart;

use Bigcommerce\Api\Client;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\CommonRuntimeException;
use Src\Core\Domain\Model\FlusherInterface;
use Src\Core\Domain\Model\Webhook\WebhookDto;
use Src\Core\Domain\Model\Webhook\WebhookProcessor;
use Src\Core\Infrastructure\Domain\Model\ClientConfigurator;

final class CartWebhookProcessor implements WebhookProcessor
{
    private IntegrationRepository $integrations;

    private CartRepository $carts;

    private FlusherInterface $flusher;

    private ClientConfigurator $clientConfigurator;

    public function __construct(IntegrationRepository $integrations, CartRepository $carts, FlusherInterface $flusher, ClientConfigurator $clientConfigurator)
    {
        $this->integrations = $integrations;
        $this->carts = $carts;
        $this->flusher = $flusher;
        $this->clientConfigurator = $clientConfigurator;
    }

    public function process(WebhookDto $dto): void
    {
        /** @var CartData $data */
        $data = $dto->getData();
        $integration = $this->integrations->getByStoreHash($dto->getHash());
        $this->clientConfigurator->configureV3($integration);
        $cartRaw = Client::getResource(sprintf('/carts/%s', $data->getCartId()));
        if (!$cartRaw) {
            throw new CommonRuntimeException('Wrong cart response: ' . Client::getLastError());
        }

        $log = new \Monolog\Logger('wh');
        $log->pushHandler(new \Monolog\Handler\StreamHandler(ROOT_DIR . '/var/log/cart.log'));
        $log->warning('Cart API response', (array)$cartRaw);

        $cart = $this->carts->findById($data->getCartId());

        if (null === $cart) {
            $cart = new Cart($data->getCartId(), (array)$cartRaw->fields->data);
            $this->carts->add($cart);
        } else {
            $cart->updatePayload((array)$cartRaw->fields->data);
        }

        $this->flusher->flush($cart);
    }
}
