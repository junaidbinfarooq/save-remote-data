<?php

namespace App\Service;

use App\Entity\Address;
use App\Entity\Bank;
use App\Entity\Hair;
use App\Entity\User;
use App\Repository\AddressRepository;
use App\Repository\BankRepository;
use App\Repository\HairRepository;
use App\Repository\UserRepository;

final class SaveUsers
{
    public function __construct(
        private readonly FetchRemoteData   $fetchRemoteData,
        private readonly UserRepository    $userRepository,
        private readonly AddressRepository $addressRepository,
        private readonly BankRepository    $bankRepository,
        private readonly HairRepository    $hairRepository,
    )
    {
    }

    public function __invoke(): int
    {
        $users = ($this->fetchRemoteData)(FetchRemoteData::RESOURCE_USERS);
        $numberOfUsersInserted = 0;

        foreach ($users as $user) {
            if (
                0 === \strlen($user['firstName']) &&
                0 === \strlen($user['lastName']) &&
                0 === \strlen($user['username']) &&
                0 === \strlen($user['email'])
            ) {
                continue;
            }

            $numberOfUsersInserted++;

            $addressEntity = new Address(
                address: $user['address']['address'] ?? '',
                city: $user['address']['city'] ?? '',
                postalCode: $user['address']['postalCode'] ?? '',
                state: $user['address']['state'] ?? '',
            );

            $this->addressRepository->add($addressEntity);

            $hairEntity = new Hair(
                color: $user['hair']['color'] ?? '',
                type: $user['hair']['type'] ?? '',
            );

            $this->hairRepository->add($hairEntity);

            $userEntity = new User(
                firstName: $user['firstName'],
                lastName: $user['lastName'],
                username: $user['username'],
                email: $user['email'],
                phone: $user['phone'],
                birthDate: \DateTime::createFromFormat('Y-m-d', $user['birthDate']),
                height: $user['height'],
                weight: $user['weight'],
            );
            $userEntity->addAddress($addressEntity);
            $userEntity->setHair($hairEntity);

            if (0 !== \count($user['bank'] ?? [])) {
                $bankEntity = new Bank(
                    cardExpire: $user['bank']['cardExpire'] ?? '',
                    cardNumber: $user['bank']['cardNumber'] ?? '',
                    cardType: $user['bank']['cardType'] ?? '',
                    currency: $user['bank']['currency'] ?? '',
                    iban: $user['bank']['iban'] ?? '',
                );

                $userEntity->addBank($bankEntity);

                $this->bankRepository->add($bankEntity);
            }

            $this->userRepository->add($userEntity);
        }

        $this->userRepository->save();

        return $numberOfUsersInserted;
    }
}
