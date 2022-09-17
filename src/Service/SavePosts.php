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
    public function __invoke(?int $userId = null): int
    {
        $userEntity = null !== $userId ? $this->userRepository->find($userId) : null;

        if (null !== $userId && null === $userEntity) {
            throw new \InvalidArgumentException(\sprintf('User with id %d was not found in the database!', $userId));
        }

        $resourceToFetch = FetchRemoteData::RESOURCE_POSTS.(null !== $userId ? 'user/'.$userId : '');

        $posts = ($this->fetchRemoteData)($resourceToFetch)['posts'] ?? [];
        $numberOfPostsInserted = 0;

        foreach ($posts as $post) {
            if (
                0 === \strlen($post['title']) &&
                0 === \strlen($post['body'])
            ) {
                continue;
            }

            $userEntity = null === $userId ? $this->userRepository->find($post['userId']) : $userEntity;

            if (null === $userEntity) {
                continue;
            }

            $numberOfPostsInserted++;

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

        return $numberOfPostsInserted;
    }
}
