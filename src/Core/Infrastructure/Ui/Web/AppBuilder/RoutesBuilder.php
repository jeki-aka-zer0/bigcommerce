<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\AppBuilder;

use Slim\Routing\RouteCollectorProxy;
use Src\Core\Infrastructure\Ui\Web\Action;
use Src\Core\Infrastructure\Ui\Web\Action\BigCommerce\Auth\Form as AuthForm;
use Src\Core\Infrastructure\Ui\Web\Action\BigCommerce\Load\Form as LoadForm;
use Src\Core\Infrastructure\Ui\Web\Action\BigCommerce\Update\Form as UpdateForm;
use Src\Core\Infrastructure\Ui\Web\Action\BigCommerce\Uninstall\Form as UninstallForm;
use Src\Core\Infrastructure\Ui\Web\Action\BigCommerce\Link\Form as LinkForm;
use Src\Core\Infrastructure\Ui\Web\Action\BigCommerce\CartRedirect\Form as CartRedirectForm;
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

        $this->getApp()->get('/test', Action\Test\Action::class . '::handle');

        $this->getApp()->group(
            '/big-commerce',
            function (RouteCollectorProxy $group) use ($validator): void {
                $group->get('/auth', Action\BigCommerce\Auth\Action::class . '::handle')
                    ->add(fn(Request $r, Handler $h) => (new Validation($validator, new AuthForm($r)))->process($r, $h));

                $group->get('/load', Action\BigCommerce\Load\Action::class . '::handle')
                    ->add(fn(Request $r, Handler $h) => (new Validation($validator, new LoadForm($r)))->process($r, $h));

                $group->post('/update', Action\BigCommerce\Update\Action::class . '::handle')
                    ->add(fn(Request $r, Handler $h) => (new Validation($validator, new UpdateForm($r)))->process($r, $h));

                $group->get('/uninstall', Action\BigCommerce\Uninstall\Action::class . '::handle')
                    ->add(fn(Request $r, Handler $h) => (new Validation($validator, new UninstallForm($r)))->process($r, $h));

                $group->get('/link', Action\BigCommerce\Link\Action::class . '::handle')
                    ->add(fn(Request $r, Handler $h) => (new Validation($validator, new LinkForm($r)))->process($r, $h));

                $group->get('/cart-redirect', Action\BigCommerce\CartRedirect\Action::class . '::handle')
                    ->add(fn(Request $r, Handler $h) => (new Validation($validator, new CartRedirectForm($r)))->process($r, $h));

                $group->post('/webhook/receive', Action\BigCommerce\Webhook\Receive\Action::class . '::handle');

                $group->get('/jobs', Action\BigCommerce\Job\Action::class . '::handle');
            }
        );
    }
}
