<?php

namespace App\Service;

use App\Entity\Address;
use App\Entity\Bank;
use App\Entity\Hair;
use App\Entity\User;
use App\Repository\UserRepository;

final class SaveUsers
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function __invoke(array $userData): void
    {
        foreach ($userData as $user) {
            $addressEntity = new Address();
            $addressEntity->setAddress($user['address']['address'] ?? '');
            $addressEntity->setCity($user['address']['city'] ?? '');

            $hairEntity = new Hair();
            $hairEntity->setColor($user['hair']['color'] ?? '');
            $hairEntity->setColor($user['hair']['type'] ?? '');

            $userEntity = new User();
            $userEntity->addAddress($addressEntity);
            $userEntity->setHair($hairEntity);

            if (0 !== \count($user['bank'] ?? [])) {
                $bankEntity = new Bank();
                $bankEntity->setCardExpire($user['bank']['cardExpire']);
                $bankEntity->setCardNumber($user['bank']['cardNumber']);
                $userEntity->addBank($bankEntity);
            }

            $this->userRepository->add($userEntity);
        }
    }
}
