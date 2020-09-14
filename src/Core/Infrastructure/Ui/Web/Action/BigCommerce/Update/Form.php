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
    private $triggerApiKey;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $publicApiKey;

    /**
     * @var int
     * @Assert\NotBlank()
     */
    private $abandonedPeriod;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $abandonedUnit;

    public function __construct(ServerRequestInterface $request)
    {
        $body = $request->getParsedBody();

        $this->storeHash = $body['store_hash'] ?? null;
        $this->triggerApiKey = $body['trigger_api_key'] ?? null;
        $this->publicApiKey = $body['public_api_key'] ?? null;
        $this->abandonedPeriod = $body['abandoned_period'] ?? null;
        $this->abandonedUnit = $body['abandoned_unit'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'store_hash' => $this->storeHash,
            'trigger_api_key' => $this->triggerApiKey,
            'public_api_key' => $this->publicApiKey,
            'abandoned_period' => $this->abandonedPeriod,
            'abandoned_unit' => $this->abandonedUnit,
        ];
    }
}
