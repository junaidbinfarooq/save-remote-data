<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class FetchRemoteData
{
    public const BASE_URL = 'https://dummyjson.com/';
    public const RESOURCE_USERS = 'users';
    public const RESOURCE_POSTS = 'posts';

    public function __construct(private readonly HttpClientInterface $httpClient)
    {
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function __invoke(string $resource): array
    {
        $response = $this->httpClient->request(
            'GET',
            self::BASE_URL.$resource,
        );

        return $response->toArray()[$resource] ?? [];
    }
}
