<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Serializer\Filter\GroupFilter;
use App\Repository\LogRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: LogRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get" => ["security" => "is_granted('ROLE_VIEW_LOG')"],
    ],
    itemOperations: [
        "get" => ["security" => "is_granted('ROLE_VIEW_LOG')"],
        "put" => ["security" => "is_granted('ROLE_ADD_LOG')"],
    ],
    normalizationContext: ["groups" => ["read"], "enable_max_depth" => true]
)]
#[ApiFilter(DateFilter::class, properties: ["dateTime"])]
#[ApiFilter(GroupFilter::class, arguments: ["parameterName" => "groups", "whitelist" => ["user"]])]
class Log {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[ApiProperty(identifier: true)]
    #[Groups(["read"])]
    private int $id;

    #[ORM\Column(type: 'datetime', length: 255)]
    #[NotBlank]
    private \DateTimeInterface $dateTime;

    #[ORM\ManyToOne(targetEntity: Person::class, fetch: 'EAGER', inversedBy: 'logs')]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(1)]
    #[ApiSubresource( maxDepth: 1 )]
    #[NotBlank]
    private Person $user;

    #[ORM\Column(type: 'string', length: 1023)]
    #[NotBlank]
    private string $content;

    /**
     * @param \DateTimeInterface $dateTime
     * @param Person $user
     * @param string $content
     */
    public function __construct(\DateTimeInterface $dateTime, Person $user, string $content)
    {
        $this->dateTime = $dateTime;
        $this->user = $user;
        $this->content = $content;
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
     * @return \DateTimeInterface
     */
    public function getDateTime(): \DateTimeInterface
    {
        return $this->dateTime;
    }

    /**
     * @param \DateTimeInterface $dateTime
     */
    public function setDateTime(\DateTimeInterface $dateTime): void
    {
        $this->dateTime = $dateTime;
    }

    /**
     * @return Person
     */
    public function getUser(): Person
    {
        return $this->user;
    }

    /**
     * @param Person $user
     */
    public function setUser(Person $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }


}