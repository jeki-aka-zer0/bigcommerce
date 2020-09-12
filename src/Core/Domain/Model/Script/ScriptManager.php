<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Script;

use Bigcommerce\Api\Client;
use Src\Core\Domain\Model\Api\WrongResponseException;

final class ScriptManager
{
    protected string $src;

    public function __construct(string $src)
    {
        $this->src = $src;
    }

    public function addToStore(): void
    {
        $response = Client::createResource('/content/scripts', [
            'name' => 'ManyChat Script',
            'src' => $this->src,
            'auto_uninstall' => true,
            'load_method' => 'default',
            'location' => 'footer',
            'visibility' => 'all_pages',
            'kind' => 'src',
        ]);

        if (false === $response) {
            throw new WrongResponseException('script - ' . json_encode(Client::getLastError()));
        }
    }
}
