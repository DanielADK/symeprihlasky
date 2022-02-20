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
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ApiResource(
    collectionOperations: ["get", "post"],
    itemOperations: ["get", "put", "patch"], # Deletion is missing because of archiving.
    denormalizationContext: ["groups" => ["write"]],
    normalizationContext: ["groups" => ["read"]],
)]
#[ApiFilter(BooleanFilter::class, properties: ["active"])]
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
    private $name_short;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private $name_full;

    #[ORM\Column(type: 'date')]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private $date_start;

    #[ORM\Column(type: 'date')]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private $date_end;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private $type;

    #[ORM\Column(type: 'boolean', options: ["default" => false])]
    #[Groups(["read", "write"])]
    private $active;

    #[ORM\Column(type: 'integer')]
    #[Groups(["read", "write"])]
    private $price_member;

    #[ORM\Column(type: 'integer')]
    #[Groups(["read", "write"])]
    private $price_other;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameShort(): ?string
    {
        return $this->name_short;
    }

    public function setNameShort(string $name_short): self
    {
        $this->name_short = $name_short;

        return $this;
    }

    public function getNameFull(): ?string
    {
        return $this->name_full;
    }

    public function setNameFull(string $name_full): self
    {
        $this->name_full = $name_full;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    public function setDateStart(\DateTimeInterface $date_start): self
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(\DateTimeInterface $date_end): self
    {
        $this->date_end = $date_end;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getPriceMember(): ?int
    {
        return $this->price_member;
    }

    public function setPriceMember(int $price_member): self
    {
        $this->price_member = $price_member;

        return $this;
    }

    public function getPriceOther(): ?int
    {
        return $this->price_other;
    }

    public function setPriceOther(int $price_other): self
    {
        $this->price_other = $price_other;

        return $this;
    }
}
