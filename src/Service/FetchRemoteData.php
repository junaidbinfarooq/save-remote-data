<?php

namespace App\Service;

use RuntimeException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use function array_merge;
use function count;
use function strpos;
use function substr;

final class FetchRemoteData implements RemoteApi
{
    public const BASE_URL = 'https://dummyjson.com/';
    public const RESOURCE_USERS = 'users/';
    public const RESOURCE_POSTS = 'posts/';

    public function __construct(private readonly HttpClientInterface $httpClient)
    {
    }

    public function fetchData(string $resourceUri): array
    {
        $resource = substr($resourceUri, 0, strpos($resourceUri, '/'));
        $skip = 0;
        $responseData = $this->getRemoteData(self::BASE_URL.$resourceUri.'?limit=30&skip='.$skip);

        $data = [];
        $data = array_merge($data, $responseData[$resource] ?? []);

        while (count($data) < ($responseData['total'] ?? 0)) {
            $skip += 30;

            $responseData = $this->getRemoteData(self::BASE_URL.$resourceUri.'?limit=30&skip='.$skip);
            $data = array_merge($data, $responseData[$resource] ?? []);
        }

        return $data;
    }

    private function getRemoteData(string $resource): array
    {
        try {
            $response = $this->httpClient->request(
                'GET',
                $resource,
            );

            return $response->toArray();
        } catch (
            TransportExceptionInterface |
            ClientExceptionInterface |
            DecodingExceptionInterface |
            RedirectionExceptionInterface |
            ServerExceptionInterface
        ) {
            throw new RuntimeException('Something went wrong while fetching remote data');
        }
    }
}
