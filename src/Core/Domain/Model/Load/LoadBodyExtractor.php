<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Load;

final class LoadBodyExtractor
{
    private string $clientSecret;

    public function __construct(string $clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    public function extract(string $signedPayload): array
    {
        [$encodedData, $encodedSignature] = explode('.', $signedPayload, 2);

        // decode the data
        $signature = base64_decode($encodedSignature);
        $jsonStr = base64_decode($encodedData);
        $data = json_decode($jsonStr, true);

        // confirm the signature
        $expectedSignature = hash_hmac('sha256', $jsonStr, $this->clientSecret, $raw = false);
        if (!hash_equals($expectedSignature, $signature)) {
            throw new WrongLoadPayloadException();
        }

        return $data;
    }
}
