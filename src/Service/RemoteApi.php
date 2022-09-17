<?php

namespace App\Service;

interface RemoteApi
{
    /**
     * @param string $resourceUri
     * @return array<string, mixed>
     */
    public function fetchData(string $resourceUri): array;
}