<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\Action\BigCommerce\Auth;

use Psr\Http\Message\ServerRequestInterface;
use Src\Core\Infrastructure\Ui\Web\Action\FormInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class Form implements FormInterface
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $code;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $context;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $scope;

    public function __construct(ServerRequestInterface $request)
    {
        $queryParams = $request->getQueryParams();

        $this->code = $queryParams['code'] ?? null;
        $this->context = $queryParams['context'] ?? null;
        $this->scope = $queryParams['scope'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'context' => $this->context,
            'scope' => $this->scope,
        ];
    }
}
