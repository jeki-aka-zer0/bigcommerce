<?php

declare(strict_types=1);

namespace Src\Bc\Infrastructure\Ui\Shared\AppBuilder;

use Src\Bc\Domain\Model\CommonRuntimeException;
use Symfony\Component\Dotenv\Dotenv;

final class EnvLoader
{
    private const FILE_NAME = '.env';

    private string $fileNameWithPath;

    public function __construct(string $fileName = self::FILE_NAME)
    {
        $this->setFile($fileName);
    }

    private function setFile(string $fileName): void
    {
        $fileNameWithPath = ROOT_DIR . "/{$fileName}";

        if (!file_exists($fileNameWithPath)) {
            throw new CommonRuntimeException('Can\'t find environment file.');
        }

        $this->fileNameWithPath = $fileNameWithPath;
    }

    public function load(): void
    {
        (new Dotenv(true))
            ->load($this->fileNameWithPath);
    }
}
