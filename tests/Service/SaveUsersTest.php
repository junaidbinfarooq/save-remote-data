<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Repository\AddressRepository;
use App\Repository\BankRepository;
use App\Repository\HairRepository;
use App\Repository\UserRepository;
use App\Service\RemoteApi;
use App\Service\SaveUsers;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SaveUsersTest extends KernelTestCase
{
    private ?UserRepository $userRepository = null;
    private ?AddressRepository $addressRepository = null;
    private ?BankRepository $bankRepository = null;
    private ?HairRepository $hairRepository = null;
    private ?EntityManagerInterface $entityManager = null;

    public function test_it_saves_users_successfully(): void
    {
        $userData = $this->initialUserData();

        $userEntity = new User(
            firstName: $userData[0]['firstName'],
            lastName: $userData[0]['lastName'],
            username: $userData[0]['username'],
            email: $userData[0]['email'],
            phone: $userData[0]['phone'],
            birthDate: \DateTime::createFromFormat('Y-m-d', $userData[0]['birthDate']),
            height: $userData[0]['height'],
            weight: $userData[0]['weight'],
        );

        $remoteApiMock = $this->createMock(RemoteApi::class);
        $remoteApiMock->method('fetchData')->willReturn($userData);

        $saveUsersApiService = new SaveUsers(
            $remoteApiMock,
            $this->userRepository,
            $this->addressRepository,
            $this->bankRepository,
            $this->hairRepository,
        );

        ($saveUsersApiService)();

        $savedUser = $this->userRepository->findBy([
            'firstName' => 'Abc',
            'lastName' => 'Xyz',
        ])[0] ?? null;

        self::assertNotNull($savedUser);
        self::assertInstanceOf(User::class, $savedUser);
        self::assertEquals($userEntity->getFirstName(), $savedUser->getFirstName());
        self::assertEquals($userEntity->getUsername(), $savedUser->getUsername());
    }

    protected function setUp(): void
    {
        self::bootKernel();

        $this->userRepository = static::getContainer()->get(UserRepository::class);
        $this->addressRepository = static::getContainer()->get(AddressRepository::class);
        $this->bankRepository = static::getContainer()->get(BankRepository::class);
        $this->hairRepository = static::getContainer()->get(HairRepository::class);
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $entityManager->beginTransaction();
        $entityManager->getConnection()->setAutoCommit(false);

        $this->entityManager = $entityManager;
    }

    protected function rollback(): void
    {
        if (null !== $this->entityManager && $this->entityManager->getConnection()->isTransactionActive()) {
            $this->entityManager->rollback();
        }
    }

    private function initialUserData(): array
    {
        return [
            [
                "firstName" => "Abc",
                "lastName" => "Xyz",
                "email" => "abc@example.com",
                "phone" => "+10 123 456 7890",
                "username" => "abc_xyz",
                "birthDate" => "2000-12-25",
                "height" => 189,
                "weight" => 75.4,
            ],
        ];
    }
}
