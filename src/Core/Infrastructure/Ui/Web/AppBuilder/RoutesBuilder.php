<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\AppBuilder;

use Src\Core\Infrastructure\Ui\Web\Action;
use Src\Core\Infrastructure\Ui\Web\Action\BigCommerce\Auth\Form as AuthForm;
use Src\Core\Infrastructure\Ui\Web\Action\BigCommerce\Load\Form as LoadForm;
use Src\Core\Infrastructure\Ui\Web\Validator\Validator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Src\Core\Infrastructure\Ui\Web\Middleware\ValidationMiddleware as Validation;

final class RoutesBuilder extends AbstractBuilder
{
    public function build(): void
    {
        $validator = $this->getContainer()->get(Validator::class);

        $this->getApp()->get('/', Action\Home\Action::class . '::handle');

        $this->getApp()->get('/big-commerce/auth', Action\BigCommerce\Auth\Action::class . '::handle')
            ->add(fn(Request $r, Handler $h) => (new Validation($validator, new AuthForm($r)))->process($r, $h));

        $this->getApp()->get('/big-commerce/load', Action\BigCommerce\Load\Action::class . '::handle')
            ->add(fn(Request $r, Handler $h) => (new Validation($validator, new LoadForm($r)))->process($r, $h));
    }
}
