<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Serializer\Filter\GroupFilter;
use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[UniqueEntity("name_short")]
#[ApiResource(
    collectionOperations: [
        "get" => ["security" => "is_granted('ROLE_VIEW_EVENT')"],
    ],
    itemOperations: [
        "get" => ["security" => "is_granted('ROLE_VIEW_EVENT')"],
        "put" => ["security" => "is_granted('ROLE_ADD_EVENT')"],
        "patch" => ["security" => "is_granted('ROLE_EDIT_EVENT')"]
    ], # Deletion is missing because of archiving.
    denormalizationContext: ["groups" => ["write"]],
    normalizationContext: ["groups" => ["read"]],
)]
#[ApiFilter(BooleanFilter::class, properties: ["deleted"])]
#[ApiFilter(SearchFilter::class, properties: [
    "name_short" => "partial",
    "type" => "exact"])]
#[ApiFilter(DateFilter::class, properties: ["date_start", "date_end"])]
class Event {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[ApiProperty(identifier: true)]
    #[Groups(["read"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private string $nameShort;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private string $nameFull;

    #[ORM\Column(type: 'date')]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private \DateTimeInterface $dateStart;

    #[ORM\Column(type: 'date')]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private \DateTimeInterface $dateEnd;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private string $type;

    #[ORM\Column(type: 'boolean', options: ["default" => false])]
    #[Groups(["read", "write"])]
    private bool $activeApplication;

    #[ORM\Column(type: 'boolean', options: ["default" => false])]
    #[Groups(["write"])]
    private bool $deleted;

    #[ORM\Column(type: 'integer')]
    #[Groups(["read", "write"])]
    private int $priceMember;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(["read", "write"])]
    private int $priceOther;

    #[ORM\Column(type: 'integer', options: ["default" => -1])]
    #[Groups(["read", "write"])]
    private int $capacity;

    #[ORM\ManyToOne(targetEntity: Address::class, inversedBy: 'events')]
    #[ORM\JoinColumn(referencedColumnName: "id", nullable: false, columnDefinition: "INT NOT NULL DEFAULT 1")]
    #[MaxDepth(1)]
    #[ApiSubresource( maxDepth: 1 )]
    #[Groups(["read"])]
    private Address $address;

    /**
     * @return mixed
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNameShort(): string {
        return $this->nameShort;
    }

    /**
     * @param mixed $nameShort
     */
    public function setNameShort(string $nameShort): void {
        $this->nameShort = $nameShort;
    }

    /**
     * @return mixed
     */
    public function getNameFull(): string {
        return $this->nameFull;
    }

    /**
     * @param mixed $nameFull
     */
    public function setNameFull(string $nameFull): void {
        $this->nameFull = $nameFull;
    }

    /**
     * @return mixed
     */
    public function getDateStart() {
        return $this->dateStart;
    }

    /**
     * @param mixed $dateStart
     */
    public function setDateStart($dateStart): void
    {
        $this->dateStart = $dateStart;
    }

    /**
     * @return mixed
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * @param mixed $dateEnd
     */
    public function setDateEnd($dateEnd): void
    {
        $this->dateEnd = $dateEnd;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function isActiveApplication(): bool
    {
        return $this->activeApplication;
    }

    /**
     * @param bool $activeApplication
     */
    public function setActiveApplication(bool $activeApplication): void
    {
        $this->activeApplication = $activeApplication;
    }

    /**
     * @return int
     */
    public function getCapacity(): int
    {
        return $this->capacity;
    }

    /**
     * @param int $capacity
     */
    public function setCapacity(int $capacity): void
    {
        $this->capacity = $capacity;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * @param bool $deleted
     */
    public function setDeleted(bool $deleted): void
    {
        $this->deleted = $deleted;
    }

    /**
     * @return int
     */
    public function getPriceMember(): int
    {
        return $this->priceMember;
    }

    /**
     * @param int $priceMember
     */
    public function setPriceMember(int $priceMember): void
    {
        $this->priceMember = $priceMember;
    }

    /**
     * @return int
     */
    public function getPriceOther(): int {
        return $this->priceOther ?? -1;
    }

    /**
     * @param int $priceOther
     */
    public function setPriceOther(int $priceOther): void
    {
        $this->priceOther = $priceOther;
    }

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }


}
