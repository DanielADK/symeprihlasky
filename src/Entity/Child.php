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
use App\Repository\ChildRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: ChildRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get" => ["security" => "is_granted('ROLE_VIEW_CHILD')"],
    ],
    itemOperations: [
        "get" => ["security" => "is_granted('ROLE_VIEW_CHILD')"],
        "put" => ["security" => "is_granted('ROLE_ADD_CHILD')"],
        "patch" => ["security" => "is_granted('ROLE_EDIT_CHILD')"]
    ],
    denormalizationContext: ["groups" => ["write"]],
    forceEager: false,
    normalizationContext: ["groups" => ["read"], "enable_max_depth" => true]
)]
#[ApiFilter(BooleanFilter::class, properties: ["active", "ctu_member"])]
#[ApiFilter(SearchFilter::class, properties: ["name" => "partial", "surname" => "partial", "parent" => "exact", "address" => "exact"])]
#[ApiFilter(DateFilter::class, properties: ["birth_date"])]
#[ApiFilter(GroupFilter::class, arguments: ["parameterName" => "groups", "whitelist" => ["parent"]])]
#[ApiFilter(GroupFilter::class, arguments: ["parameterName" => "groups", "whitelist" => ["applications"]])]
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
    #[Groups(["parent"])]
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
    private \DateTimeInterface $birthDate;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["read", "write"])]
    private string $sex;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["read", "write"])]
    private string $shirtSize;

    #[ORM\Column(type: 'boolean', nullable: true, options: ["default" => false])]
    #[Groups(["read", "write"])]
    private bool $ctuMember;

    #[ORM\Column(type: 'boolean', nullable: true, options: ["default" => false])]
    #[Groups(["read", "write"])]
    private bool $deleted;

    #[ORM\OneToMany(mappedBy: 'child', targetEntity: Application::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[MaxDepth(1)]
    #[ApiSubresource( maxDepth: 1 )]
    #[Groups(["applications"])]
    private ?Collection $applications;

    /**
     * @return int
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
    public function getName(): string {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSurname(): string {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname(string $surname): void {
        $this->surname = $surname;
    }

    /**
     * @return Person
     */
    public function getParent(): Person {
        return $this->parent;
    }

    /**
     * @param Person $parent
     */
    public function setParent(Person $parent): void {
        $this->parent = $parent;
    }

    /**
     * @return Address
     */
    public function getAddress(): Address {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress(Address $address): void {
        $this->address = $address;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getBirthDate(): \DateTimeInterface {
        return $this->birthDate;
    }

    /**
     * @param \DateTimeInterface $birthDate
     */
    public function setBirthDate(\DateTimeInterface $birthDate): void {
        $this->birthDate = $birthDate;
    }

    /**
     * @return string
     */
    public function getSex(): string {
        return $this->sex;
    }

    /**
     * @param string $sex
     */
    public function setSex(string $sex): void {
        $this->sex = $sex;
    }

    /**
     * @return string
     */
    public function getShirtSize(): string {
        return $this->shirtSize;
    }

    /**
     * @param string $shirtSize
     */
    public function setShirtSize(string $shirtSize): void {
        $this->shirtSize = $shirtSize;
    }

    /**
     * @return bool
     */
    public function isCtuMember(): bool {
        return $this->ctuMember;
    }

    /**
     * @param bool $ctuMember
     */
    public function setCtuMember(bool $ctuMember): void {
        $this->ctuMember = $ctuMember;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool {
        return $this->deleted;
    }

    /**
     * @param bool $deleted
     */
    public function setDeleted(bool $deleted): void {
        $this->deleted = $deleted;
    }

    /**
     * @return Collection
     */
    public function getApplications(): Collection {
        return $this->applications;
    }

    /**
     * @param Collection $applications
     */
    public function setApplications(Collection $applications): void {
        $this->applications = $applications;
    }


}