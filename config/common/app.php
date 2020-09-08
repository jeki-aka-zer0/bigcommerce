<?php

declare(strict_types=1);

use Src\Bc\Infrastructure\Translations\TranslatorFactory;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Src\Bc\Infrastructure\Ui\Web\Validator\Validator;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Log\LoggerInterface;
use Slim\Error\Renderers\JsonErrorRenderer;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Interfaces\ErrorHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Src\Bc\Infrastructure\Ui\Web\Middleware\JsonBodyParserMiddleware;
use Src\Bc\Infrastructure\Ui\Web\Middleware\ErrorsCatcherMiddleware;
use Src\Bc\Infrastructure\Ui\Shared\AppBuilder\LogErrorHandler;
use Slim\Error\Renderers\PlainTextErrorRenderer;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

return [
    ResponseFactoryInterface::class => fn() => new ResponseFactory(),

    JsonErrorRenderer::class => fn() => new JsonErrorRenderer(),

    PlainTextErrorRenderer::class => fn() => new PlainTextErrorRenderer(),

    ErrorHandlerInterface::class => function (ContainerInterface $c): LogErrorHandler {
        $logHandler = new LogErrorHandler(
            $c->get(LoggerInterface::class),
            $c->get(CallableResolverInterface::class),
            $c->get(ResponseFactoryInterface::class)
        );
        $logHandler->forceContentType('application/json');

        return $logHandler;
    },

    JsonBodyParserMiddleware::class => new JsonBodyParserMiddleware(),

    ErrorsCatcherMiddleware::class => new ErrorsCatcherMiddleware(),

    ValidatorInterface::class => function (ContainerInterface $c): ValidatorInterface {
        AnnotationRegistry::registerLoader('class_exists');

        return Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->setTranslator($c->get(TranslatorInterface::class))
            ->getValidator();
    },

    Validator::class => fn(ContainerInterface $c) => new Validator(
        $c->get(ValidatorInterface::class),
    ),

    'config' => [
        'translations' => [
            'locale' => 'ru',
            'format' => 'php',
            'dir' => ROOT_DIR . '/src/Bc/Infrastructure/Translations/data',
            'files' => [
                'validators.ru.php',
                'errors.ru.php',
            ],
        ],
    ],
];
