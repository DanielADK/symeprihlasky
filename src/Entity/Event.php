<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
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
#[ApiFilter(SearchFilter::class, properties: ["name_short" => "partial", "type" => "exact"])]
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
    private $nameShort;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private $nameFull;

    #[ORM\Column(type: 'date')]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private $dateStart;

    #[ORM\Column(type: 'date')]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private $dateEnd;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private string $type;

    #[ORM\Column(type: 'boolean', options: ["default" => false])]
    #[Groups(["read", "write"])]
    private bool $activeAssignment;

    #[ORM\Column(type: 'boolean', options: ["default" => false])]
    #[Groups(["write"])]
    private bool $deleted;

    #[ORM\Column(type: 'integer')]
    #[Groups(["read", "write"])]
    private int $priceMember;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(["read", "write"])]
    private int $priceOther;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNameShort()
    {
        return $this->nameShort;
    }

    /**
     * @param mixed $nameShort
     */
    public function setNameShort($nameShort): void
    {
        $this->nameShort = $nameShort;
    }

    /**
     * @return mixed
     */
    public function getNameFull()
    {
        return $this->nameFull;
    }

    /**
     * @param mixed $nameFull
     */
    public function setNameFull($nameFull): void
    {
        $this->nameFull = $nameFull;
    }

    /**
     * @return mixed
     */
    public function getDateStart()
    {
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
    public function isActiveAssignment(): bool
    {
        return $this->activeAssignment;
    }

    /**
     * @param bool $activeAssignment
     */
    public function setActiveAssignment(bool $activeAssignment): void
    {
        $this->activeAssignment = $activeAssignment;
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
    public function getPriceOther(): int
    {
        return $this->priceOther;
    }

    /**
     * @param int $priceOther
     */
    public function setPriceOther(int $priceOther): void
    {
        $this->priceOther = $priceOther;
    }


}
