<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Serializer\Filter\GroupFilter;
use App\Repository\ApplicationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Unique;

#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
#[UniqueEntity("hash")]
#[ApiResource(
    collectionOperations: [
        "get" => ["security" => "is_granted('ROLE_VIEW_APPLICATION')"],
    ],
    itemOperations: [
//        "get" => ["security" => "is_granted('ROLE_VIEW_APPLICATION')"],
        "get",
        "put" => ["security" => "is_granted('ROLE_ADD_APPLICATION')"],
        "patch" => ["security" => "is_granted('ROLE_EDIT_APPLICATION')"],
        "delete" => ["security" => "is_granted('ROLE_DELETE_APPLICATION')"]
    ],
    denormalizationContext: ["groups" => ["write"]],
    normalizationContext: ["groups" => ["read"]],
)]
#[ApiFilter(SearchFilter::class, properties: [
    "hash" => "exact",
    "event" => "exact",
    "person" => "exact"])]
#[ApiFilter(DateFilter::class, properties: ["sign_date"])]
#[ApiFilter(GroupFilter::class, arguments: ["parameterName" => "groups", "whitelist" => ["event", "person", "child","person.roles"]])]
class Application {
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 6)]
    #[Groups(["read"])]
    #[Unique]
    #[NotBlank]
    private string $hash;

    #[ORM\ManyToOne(targetEntity: Event::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[NotBlank]
    #[MaxDepth(2)]
    #[ApiSubresource( maxDepth: 2 )]
    #[Groups(["event"])]
    private Event $event;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: true)]
    #[MaxDepth(2)]
    #[ApiSubresource( maxDepth: 2 )]
    #[Groups(["person", "person.roles"])]
    private ?Person $person;

    #[ORM\ManyToOne(targetEntity: Child::class, inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: true)]
    #[MaxDepth(1)]
    #[ApiSubresource( maxDepth: 1 )]
    #[Groups(["child"])]
    private ?Child $child;

    #[ORM\Column(type: 'datetime')]
    #[Groups(["read"])]
    private \DateTimeInterface $signDate;


    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read", "write"])]
    private string $shirtSize;

    public function getPerson(): ?Person {
        return $this->person;
    }

    public function setPerson(?Person $person): self {
        $this->person = $person;

        return $this;
    }

    /**
     * @return Child
     */
    public function getChild(): ?Child {
        return $this->child;
    }

    /**
     * @param Child $child
     */
    public function setChild(Child $child): void {
        $this->child = $child;
    }

    /**
     * @return string
     */
    public function getDeleted(): string
    {
        return $this->deleted;
    }

    /**
     * @param string $deleted
     */
    public function setDeleted(string $deleted): void
    {
        $this->deleted = $deleted;
    }

    public function getSignDate(): ?\DateTimeInterface {
        return $this->signDate;
    }

    public function setSignDate(\DateTimeInterface $sign_date): self  {
        $this->signDate = $sign_date;
        return $this;
    }

    public function getHash(): ?string {
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
