<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get" => ["security" => "is_granted('ROLE_VIEW_ADDRESS')"],
        "post" => ["security" => "is_granted('ROLE_ADD_ADDRESS')"],
    ],
    itemOperations: [
        "get" => ["security" => "is_granted('ROLE_VIEW_ADDRESS')"],
        "put" => ["security" => "is_granted('ROLE_ADD_ADDRESS')"],
        "patch" => ["security" => "is_granted('ROLE_EDIT_ADDRESS')"]
    ], # Deletion is missing because of archiving.
    denormalizationContext: ["groups" => ["write"]],
    normalizationContext: ["groups" => ["read"]],
)]
#[ApiFilter(SearchFilter::class, properties: ["street" => "partial", "city" => "partial", "postcode" => "exact", "people" => "partial"])]
class Address {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[ApiProperty(identifier: true)]
    #[Groups(["read"])]
    private int $id;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private string $street;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private string $city;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private string $postcode;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read", "write"])]
    private string $country;

    #[ORM\OneToMany(mappedBy: 'address', targetEntity: Person::class)]
    #[MaxDepth(1)]
    #[ApiSubresource( maxDepth: 1 )]
    private Collection $people;

    public function __construct()
    {
        $this->people = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param string $street
     */
    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getPostcode(): string
    {
        return $this->postcode;
    }

    /**
     * @param string $postcode
     */
    public function setPostcode(string $postcode): void
    {
        $this->postcode = $postcode;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getPeople(): ArrayCollection|Collection
    {
        return $this->people;
    }

    /**
     * @param ArrayCollection|Collection $people
     */
    public function setPeople(ArrayCollection|Collection $people): void
    {
        $this->people = $people;
    }


}
