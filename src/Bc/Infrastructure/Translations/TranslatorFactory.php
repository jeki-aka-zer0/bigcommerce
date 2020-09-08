<?php

declare(strict_types=1);

namespace Src\Bc\Infrastructure\Translations;

use Psr\Container\ContainerInterface;
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;

final class TranslatorFactory
{
    public static function build(ContainerInterface $c): TranslatorInterface
    {
        $config = $c->get('config')['translations'];
        $translator = new Translator($config['locale']);
        $translator->addLoader($config['format'], new PhpFileLoader());

        array_map(
            fn($fileName) => $translator->addResource(
                $config['format'],
                $config['dir'] . DIRECTORY_SEPARATOR . $fileName,
                self::getLocaleFromFileName($fileName)
            ),
            $config['files']
        );

        return $translator;
    }

    private static function getLocaleFromFileName(string $fileName): string
    {
        return explode('.', $fileName)[1];
    }
}
