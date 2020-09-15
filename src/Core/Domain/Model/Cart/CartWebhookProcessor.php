<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Cart;

use Bigcommerce\Api\Client;
use DateTimeImmutable;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\CommonRuntimeException;
use Src\Core\Domain\Model\FlusherInterface;
use Src\Core\Domain\Model\Id;
use Src\Core\Domain\Model\Job\Job;
use Src\Core\Domain\Model\Job\JobRepository;
use Src\Core\Domain\Model\Job\Sign;
use Src\Core\Domain\Model\Webhook\WebhookDto;
use Src\Core\Domain\Model\Webhook\WebhookProcessor;
use Src\Core\Infrastructure\Domain\Model\ClientConfigurator;

final class CartWebhookProcessor implements WebhookProcessor
{
    public const TRIGGER_KEY_CART = 'abandoned_cart';

    private IntegrationRepository $integrations;

    private CartRepository $carts;

    private FlusherInterface $flusher;

    private ClientConfigurator $clientConfigurator;

    private JobRepository $jobs;

    public function __construct(IntegrationRepository $integrations, CartRepository $carts, FlusherInterface $flusher, ClientConfigurator $clientConfigurator, JobRepository $jobs)
    {
        $this->integrations = $integrations;
        $this->carts = $carts;
        $this->flusher = $flusher;
        $this->clientConfigurator = $clientConfigurator;
        $this->jobs = $jobs;
    }

    public function process(WebhookDto $dto): void
    {
        /** @var CartData $data */
        $data = $dto->getData();
        $integration = $this->integrations->getByStoreHash($dto->getHash());
        $this->clientConfigurator->configureV3($integration);
        $cartResource = Client::getResource(sprintf('/carts/%s', $data->getCartId()));
        if (!$cartResource) {
            throw new CommonRuntimeException('Wrong cart response: ' . Client::getLastError()); // @todo fix
        }

        $log = new \Monolog\Logger('wh');
        $log->pushHandler(new \Monolog\Handler\StreamHandler(ROOT_DIR . '/var/log/cart.log'));
        $log->warning('Cart API response', (array)$cartResource->data);

        $cart = $this->carts->findById($data->getCartId());

        if (null === $cart) {
            $cart = new Cart($data->getCartId(), (array)$cartResource->data);
            $this->carts->add($cart);
        } else {
            $cart->updatePayload((array)$cartResource->data);
        }

        $sign = Sign::build(self::TRIGGER_KEY_CART, $cart->getId());
        $job = $this->jobs->findBySign($sign);

        // @todo move
        $units = ['minutes', 'hours', 'days'];
        $unit = \in_array($integration->getAbandonedUnit(), $units, true) ? $integration->getAbandonedUnit() : $units[1];
        $period = $integration->getAbandonedPeriod();
        if ($period < 0) {
            $period = 1;
        }

        $scheduledAt = (new DateTimeImmutable())->modify(sprintf('+ %d %s', $period, $unit));

        if (null === $job) {
            $job = new Job(Id::next(), $sign, $integration, $scheduledAt);
            $this->jobs->add($job);
        } else {
            $job->reschedule($scheduledAt);
        }

        $this->flusher->flush($cart, $job);
    }
}
