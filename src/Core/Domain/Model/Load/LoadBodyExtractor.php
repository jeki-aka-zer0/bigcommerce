<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Auth;

final class LoadBodyExtractor
{
    private CredentialsDto $credentials;

    public function __construct(CredentialsDto $credentials)
    {
        $this->credentials = $credentials;
    }

    public function extract(string $signedPayload): array
    {
        [$encodedData, $encodedSignature] = explode('.', $signedPayload, 2);

        // decode the data
        $signature = base64_decode($encodedSignature);
        $jsonStr = base64_decode($encodedData);
        $data = json_decode($jsonStr, true);

        // confirm the signature
        $expectedSignature = hash_hmac('sha256', $jsonStr, $this->credentials->client_secret, $raw = false);
        if (!hash_equals($expectedSignature, $signature)) {
            throw new WrongSignedPayloadException();
        }

        return $data;
    }
}
