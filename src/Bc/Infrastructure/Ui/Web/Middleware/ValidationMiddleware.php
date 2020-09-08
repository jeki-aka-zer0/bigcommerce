<?php

declare(strict_types=1);

namespace Src\Bc\Infrastructure\Ui\Web\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Src\Bc\Infrastructure\Ui\Web\Action\FormInterface;
use Src\Bc\Infrastructure\Ui\Web\Validator\Validator;

final class ValidationMiddleware implements MiddlewareInterface
{
    private Validator $validator;

    private FormInterface $form;

    public function __construct(Validator $validator, FormInterface $form)
    {
        $this->validator = $validator;
        $this->form = $form;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($errors = $this->validator->validate($this->form)) {
            throw new ValidationException($errors);
        }

        foreach ($this->form->toArray() as $name => $value) {
            $request = $request->withAttribute($name, $value);
        }

        return $handler->handle($request);
    }
}
