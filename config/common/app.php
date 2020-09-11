<?php

declare(strict_types=1);

use Doctrine\Common\Annotations\AnnotationRegistry;
use Src\Core\Infrastructure\Domain\Model\ClientConfigurator;
use Src\Core\Infrastructure\Ui\Web\Validator\Validator;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Log\LoggerInterface;
use Slim\Error\Renderers\JsonErrorRenderer;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Interfaces\ErrorHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Src\Core\Infrastructure\Ui\Web\Middleware\JsonBodyParserMiddleware;
use Src\Core\Infrastructure\Ui\Web\Middleware\ErrorsCatcherMiddleware;
use Src\Core\Infrastructure\Ui\Shared\AppBuilder\LogErrorHandler;
use Slim\Error\Renderers\PlainTextErrorRenderer;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Templating\Helper\SlotsHelper;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Src\Core\Domain\Model\Load\LoadBodyExtractor;

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

    ValidatorInterface::class => function (): ValidatorInterface {
        AnnotationRegistry::registerLoader('class_exists');

        return Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();
    },

    Validator::class => fn(ContainerInterface $c) => new Validator(
        $c->get(ValidatorInterface::class),
    ),

    LoadBodyExtractor::class => fn(ContainerInterface $c) => new LoadBodyExtractor(
        $c->get('config')['credentials']['clientSecret'],
    ),

    PhpEngine::class => function(ContainerInterface $c) {
        $filesystemLoader = new FilesystemLoader(ROOT_DIR.'/src/Core/Infrastructure/Ui/Web/Action/%name%');

        $templating = new PhpEngine(new TemplateNameParser(), $filesystemLoader);
        $templating->set(new SlotsHelper());

        return $templating;
    },

    ClientConfigurator::class => fn(ContainerInterface $c) => new ClientConfigurator(
        $c->get('config')['credentials']['clientId'],
    ),
];
