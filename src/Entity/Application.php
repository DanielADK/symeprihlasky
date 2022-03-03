<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\ApplicationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
#[UniqueEntity("hash")]
#[ApiResource(
    collectionOperations: [
        "get" => ["security" => "is_granted('ROLE_VIEW_APPLICATION')"],
    ],
    itemOperations: [
        "get" => ["security" => "is_granted('ROLE_VIEW_APPLICATION')"],
        "put" => ["security" => "is_granted('ROLE_ADD_APPLICATION')"],
        "patch" => ["security" => "is_granted('ROLE_EDIT_APPLICATION')"]
    ], # Deletion is missing because of archiving.
    denormalizationContext: ["groups" => ["write"]],
    normalizationContext: ["groups" => ["read"]],
)]
#[ApiFilter(SearchFilter::class, properties: ["hash" => "exact", "event" => "exact", "person" => "exact"])]
#[ApiFilter(DateFilter::class, properties: ["sign_date"])]
class Application {
    #[ORM\Column(type: 'string', length: 255)]
    #[ORM\Id]
    #[Groups(["read"])]
    #[ApiProperty(identifier: true)]
    #[NotBlank]
    private string $hash;

    #[ORM\ManyToOne(targetEntity: Event::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[NotBlank]
    #[MaxDepth(2)]
    #[ApiSubresource( maxDepth: 2 )]
    #[Groups(["read"])]
    private Event $event;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    #[NotBlank]
    #[MaxDepth(1)]
    #[ApiSubresource( maxDepth: 1 )]
    #[Groups(["read"])]
    private Person $person;

    #[ORM\Column(type: 'datetime')]
    #[Groups(["read"])]
    private \DateTimeInterface $sign_date;


    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read", "write"])]
    private string $shirtSize;

    #[ORM\Column(type: 'boolean', options: ["default" => false])]
    #[Groups(["read", "write"])]
    private string $deleted;

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

        return $this;
    }

    public function getSignDate(): ?\DateTimeInterface
    {
        return $this->sign_date;
    }

    public function setSignDate(\DateTimeInterface $sign_date): self
    {
        $this->sign_date = $sign_date;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getShirtSize(): ?string
    {
        return $this->shirtSize;
    }

    public function setShirtSize(string $shirt_size): self
    {
        $this->shirtSize = $shirt_size;

        return $this;
    }

    /**
     * @return Event
     */
    public function getEvent(): Event
    {
        return $this->event;
    }

    /**
     * @param Event $event
     */
    public function setEvent(Event $event): void
    {
        $this->event = $event;
    }

}
