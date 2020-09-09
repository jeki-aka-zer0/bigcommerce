<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\AppBuilder;

use Laminas\Diactoros\Response\HtmlResponse;
use Src\Core\Infrastructure\Ui\Web\Action;
use Src\Core\Infrastructure\Ui\Web\Validator\Validator;

final class RoutesBuilder extends AbstractBuilder
{
    public function build(): void
    {
        $validator = $this->getContainer()->get(Validator::class);

        $this->getApp()->get('/', Action\Home\Action::class . '::handle');
        $this->getApp()->get('/big-commerce/auth', Action\BigCommerce\Auth\Action::class . '::handle');
        $this->getApp()->get('/big-commerce/load', fn() => new HtmlResponse('Success'));
    }
}
