<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\ChildRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: ChildRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get" => ["security" => "is_granted('ROLE_VIEW_CHILD')"],
        "post" => ["security" => "is_granted('ROLE_ADD_CHILD')"],
    ],
    itemOperations: [
        "get" => ["security" => "is_granted('ROLE_VIEW_CHILD')"],
        "put" => ["security" => "is_granted('ROLE_ADD_CHILD')"],
        "patch" => ["security" => "is_granted('ROLE_EDIT_CHILD')"]
    ], # Deletion is missing because of archiving.
    denormalizationContext: ["groups" => ["write"]],
    normalizationContext: ["groups" => ["read"]],
)]
#[ApiFilter(BooleanFilter::class, properties: ["active", "ctu_member"])]
#[ApiFilter(SearchFilter::class, properties: ["name" => "partial", "surname" => "partial", "parent" => "exact", "address" => "exact"])]
#[ApiFilter(DateFilter::class, properties: ["birth_date"])]
class Child extends EntityRepository {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[ApiProperty(identifier: true)]
    #[Groups(["read"])]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private string $surname;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'children')]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(1)]
    #[ApiSubresource( maxDepth: 1 )]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private Person $parent;

    #[ORM\ManyToOne(targetEntity: Address::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(1)]
    #[ApiSubresource( maxDepth: 1 )]
    #[Groups(["read", "write"])]
    private Address $address;

    #[ORM\Column(type: 'date', nullable: true)]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private \DateTimeInterface $birth_date;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["read", "write"])]
    private string $sex;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["read", "write"])]
    private string $shirt_size;

    #[ORM\Column(type: 'boolean', nullable: true, options: ["default" => false])]
    #[Groups(["read", "write"])]
    private bool $ctu_member;

    #[ORM\Column(type: 'boolean', nullable: true, options: ["default" => true])]
    #[Groups(["read", "write"])]
    private bool $active;

    #[ORM\OneToMany(mappedBy: 'person', targetEntity: Application::class)]
    #[MaxDepth(1)]
    #[ApiSubresource(
        maxDepth: 1
    )]
    #[Groups(["read", "write"])]
    private Collection $applications;

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
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

    /**
     * @return \DateTimeInterface
     */
    public function getBirthDate(): \DateTimeInterface
    {
        return $this->birth_date;
    }

    /**
     * @param \DateTimeInterface $birth_date
     */
    public function setBirthDate(\DateTimeInterface $birth_date): void
    {
        $this->birth_date = $birth_date;
    }

    /**
     * @return string
     */
    public function getSex(): string
    {
        return $this->sex;
    }

    /**
     * @param string $sex
     */
    public function setSex(string $sex): void
    {
        $this->sex = $sex;
    }

    /**
     * @return string
     */
    public function getShirtSize(): string
    {
        return $this->shirt_size;
    }

    /**
     * @param string $shirt_size
     */
    public function setShirtSize(string $shirt_size): void
    {
        $this->shirt_size = $shirt_size;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return bool
     */
    public function isCtuMember(): bool
    {
        return $this->ctu_member;
    }

    /**
     * @param bool $ctu_member
     */
    public function setCtuMember(bool $ctu_member): void
    {
        $this->ctu_member = $ctu_member;
    }

    /**
     * @return Collection
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    /**
     * @param Collection $applications
     */
    public function setApplications(Collection $applications): void
    {
        $this->applications = $applications;
    }

    /**
     * @return Person
     */
    public function getParent(): Person
    {
        return $this->parent;
    }

    /**
     * @param Person $parent
     */
    public function setParent(Person $parent): void
    {
        $this->parent = $parent;
    }


}