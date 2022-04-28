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
#[ORM\UniqueConstraint(name: "address", columns: ["street", "city", "postcode"])]
#[ApiResource(
    collectionOperations: [
        "get" => ["security" => "is_granted('ROLE_VIEW_ADDRESS')"],
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

    #[ORM\OneToMany(mappedBy: 'address', targetEntity: Person::class)]
    #[MaxDepth(1)]
    #[ApiSubresource( maxDepth: 1 )]
    private Collection $people;

    public function __construct(
        string $street,
        string $city,
        string $postcode
    ) {
        $this->street = $street;
        $this->city = $city;
        $this->postcode = $postcode;

        $this->people = new ArrayCollection();
    }
    public function deepCopy(Address $address) {
        $this->street = $address->street;
        $this->city = $address->city;
        $this->postcode = $address->postcode;
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

    public function __toString(): string {
        return "{\"id\":$this->id, \"street\":$this->street, \"city\":$this->city, \"postcode\":$this->postcode}";
    }


    public static function cmp(Address &$a1, Address &$a2): bool {
        return ($a1->getStreet() == $a2->getStreet()) &&
            ($a1->getCity() == $a2->getCity()) &&
            ($a1->getPostcode() == $a2->getPostcode());
    }


}
