<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\Action\BigCommerce\Auth;

use Bigcommerce\Api\Client;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Action implements RequestHandlerInterface
{

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        var_dump($request->getAttribute('code'));
        var_dump($request->getAttribute('context'));
        var_dump($request->getAttribute('scope'));
//        exit;

        $object = new \stdClass();

        // todo move to global
        $clientId = '36j3cwu6kcwj5ne43oizbagywtq4o7f';
        $clientSecret = 'a3b8f147e607c2f7d8929203e639e1ba5629982ebad59395b2b968e8584acbba';
        $redirectUri = 'https://gh3yu.sse.codesandbox.io/auth';

        $object->client_id = $clientId;
        $object->client_secret = $clientSecret;
        $object->redirect_uri = $redirectUri;

        // todo validate
        $object->code = $request->getAttribute('code');
        $object->context = $request->getAttribute('context');
        $object->scope = $request->getAttribute('scope');

        $authTokenResponse = Client::getAuthToken($object);

        var_dump($authTokenResponse);

//        Client::configure([
//            'client_id' => '$clientId',
//            'auth_token' => $authTokenResponse->access_token,
//            'store_hash' => 'xxxxxxx'
//        ]);

        return new JsonResponse([]);
    }
}
