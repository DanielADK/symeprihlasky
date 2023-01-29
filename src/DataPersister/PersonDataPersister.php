<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Address;
use App\Entity\Log;
use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;

class PersonDataPersister implements DataPersisterInterface {
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $userPasswordHasher;
    private Security $security;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher,
        Security $security) {
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->security = $security;
    }

    public function supports($data): bool {
        return $data instanceof Person;
    }

    public function persist($data): void {
        $repository = $this->entityManager->getRepository(Address::class);
        $address = $data->getAddress();
        if ($address) {
            $newAddress = $repository->findOneBy(
                    array('id' => $address->getId())
            );

            /** Changed address? */
            if (!Address::cmp($newAddress, $address)) {
//                error_log("Neshoda!");
                $existing = $repository->findOneBy(
                    array('street' => $address->getStreet()),
                    array('city' => $address->getCity()),
                    array('postcode' => $address->getPostcode())
                );

                if (!Address::cmp($existing, $address)) {
                    if ($existing !== null) {
                        $data->setAddress($existing);
//                        error_log("Existuje!".$data->getAddress()->getId());
                    } else {
//                        error_log(print_r($address->getId(), TRUE));
                        $newAddress = new Address(
                            $address->getStreet(),
                            $address->getCity(),
                            $address->getPostcode()
                        );
                        $this->entityManager->persist($newAddress);
//                        error_log(print_r($newAddress->getId(), TRUE));
                        $data->setAddress($newAddress);
//                        error_log("Vytvářím novou!".$newAddress->getId(), TRUE);
                    }
                }
            }

        }
        $person = $this->entityManager->getRepository(Person::class)->findOneBy(array("id" => $data->getId()));
        if ($data->getPassword() != $person->getPassword()) {
//            error_log("PASSWORD CHANGED:");
//            error_log(print_r($data->getPassword()));
            $data->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $data,
                    $data->getPassword()
                )
            );
            $data->eraseCredentials();

        }
        $this->entityManager->persist(
            new Log(
                new \DateTime,
                $this->entityManager->getRepository(Person::class)->findOneBy(array("email" => $this->security->getUser()->getUserIdentifier())),
                "Byl upraven dospělý uživatel " . $data->getFullName() . "(" . $data->getId() . ")."
            )
        );
        $this->entityManager->persist($data->getAddress());
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data) {
        $this->entityManager->remove($data);
    }
}