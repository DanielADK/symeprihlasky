<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Address;
use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PersonDataPersister implements DataPersisterInterface {
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher) {
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function supports($data): bool {
        return $data instanceof Person;
    }

    public function persist($data) {
        $address = $data->getAddress();
        if ($address) {
            $this->entityManager->detach($address);
            $newAddress = $this->entityManager
                ->getRepository(Address::class)->findById(3);
            error_log(print_r($address->getId(), TRUE));
            error_log(print_r($address->getStreet(), TRUE));
            error_log(print_r($newAddress->getId(), TRUE));
            error_log(print_r($newAddress->getStreet(), TRUE));

            if (!Address::cmp($newAddress, $address)) {
                $existing = $this->entityManager
                    ->getRepository(Address::class)
                    ->findByFullText(
                        $address->getStreet(),
                        $address->getCity(),
                        $address->getPostcode()
                    );

                if ($existing !== null) {
                    $data->setAddress($existing);
                    error_log("Existuje!".$data->getAddress()->getId());
                } else {
                    error_log(print_r($address->getId(), TRUE));
                    $newAddress = new Address(
                        $address->getStreet(),
                        $address->getCity(),
                        $address->getPostcode()
                    );
                    $this->entityManager->persist($newAddress);
                    $this->entityManager->flush($newAddress);
                    error_log(print_r($address->getId(), TRUE));
                    $data->setAddress($newAddress);
                    error_log("Vytvářím novou!".$newAddress->getId(), TRUE);
                }
            }

        }

        if ($data->getPassword()) {
            $data->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $data,
                    $data->getPassword()
                )
            );
            $data->eraseCredentials();

        }
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data) {
        $this->dataPersister->remove($data);
    }
}