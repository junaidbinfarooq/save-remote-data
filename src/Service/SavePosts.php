<?php

namespace App\Service;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class SavePosts
{
    public function __construct(
        private readonly FetchRemoteData $fetchRemoteData,
        private readonly UserRepository  $userRepository,
        private readonly PostRepository  $postRepository,
    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function __invoke(): int
    {
        $posts = ($this->fetchRemoteData)(FetchRemoteData::RESOURCE_POSTS);

        foreach ($posts as $post) {
            if (
                0 === \strlen($post['title']) &&
                0 === \strlen($post['body'])
            ) {
                continue;
            }

            $userEntity = $this->userRepository->find($post['userId']);

            if (null === $userEntity) {
                continue;
            }

            $postEntity = new Post(
                title: $post['title'] ?? '',
                body: $post['body'] ?? '',
                reactions: $post['reactions'] ?? '',
            );

            $this->postRepository->add($postEntity);

            $userEntity->addPost($postEntity);

            $this->userRepository->add($userEntity);
        }

        $this->userRepository->save();

        return \count($posts);
    }
}
