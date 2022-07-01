<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Elasticsearch\DataProvider\Filter\MatchFilter;
use ApiPlatform\Core\Bridge\Elasticsearch\DataProvider\Filter\TermFilter;
use ApiPlatform\Core\Serializer\Filter\GroupFilter;
use App\Repository\PersonRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method null loadUserByIdentifier(string $identifier)
 */
#[ORM\Entity(repositoryClass: PersonRepository::class)]
#[UniqueEntity("email")]
#[ApiResource(
    collectionOperations: [
        "get" => ["security" => "is_granted('ROLE_VIEW_PERSON')"],
    ],
    itemOperations: [
        "get" => ["security" => "is_granted('ROLE_VIEW_PERSON')"],
        "put" => ["security" => "is_granted('ROLE_ADD_PERSON')"],
        "patch" => ["security" => "is_granted('ROLE_EDIT_PERSON')"]
    ], # Deletion is missing because of archiving.
    denormalizationContext: ["groups" => ["write"]],
    forceEager: false,
    normalizationContext: ["groups" => ["read"]]
)]
#[ApiFilter(BooleanFilter::class, properties: ["deleted", "ctu_member"])]
#[ApiFilter(SearchFilter::class, properties: [
    "name" => "partial",
    "surname" => "partial",
    "address" => "exact"])]
#[ApiFilter(GroupFilter::class, arguments: ["parameterName" => "groups", "whitelist" => ["children", "applications", "address", "roles"]])]
class Person implements UserInterface, PasswordAuthenticatedUserInterface {
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

    #[ORM\ManyToOne(targetEntity: Address::class, fetch: 'EAGER', inversedBy: 'people')]
    #[ORM\JoinColumn(nullable: true)]
    #[MaxDepth(1)]
    #[ApiSubresource( maxDepth: 1 )]
    #[Groups(["address"])]
    private Address $address;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: Child::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[MaxDepth(1)]
    #[ApiSubresource( maxDepth: 1 )]
    #[Groups(["children"])]
    private ?Collection $children;

    #[ORM\Column(type: 'date', nullable: true)]
    #[Groups(["read", "write"])]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["read", "write"])]
    private ?string $sex;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["read", "write"])]
    private ?string $shirtSize;

    #[ORM\Column(type: 'string', length: 255, unique: true, nullable: false)]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private string $email;

    #[ORM\Column(type: 'string', length: 255, unique: true, nullable: true)]
    #[Groups(["read", "write"])]
    private ?string $phone;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Groups(["write"])]
    private string $password;

    #[ORM\Column(type: 'boolean', nullable: true, options: ["default" => false])]
    #[Groups(["read", "write"])]
    private ?bool $ctuMember = false;

    #[ORM\Column(type: 'boolean', options: ["default" => false])]
    #[Groups(["read", "write"])]
    private bool $deleted = false;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(["roles", "person.roles"])]
    private ?array $roles = [];

    #[ORM\OneToMany(mappedBy: 'person', targetEntity: Application::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[MaxDepth(1)]
    #[ApiSubresource( maxDepth: 1 )]
    #[Groups(["applications"])]
    private ?Collection $applications;


    public function getUserIdentifier(): string {
        return $this->email;
    }

    public function getSalt(): ?string {
        return null;
    }

    public function eraseCredentials() {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): string {
        return $this->name . '-' . $this->surname;
    }
    public function getFullname(): string {
        return $this->name . ' ' . $this->surname;
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
     * @return string|null
     */
    public function getSurname(): ?string
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
     * @return Address|null
     */
    public function getAddress(): ?Address
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
     * @return Collection|null
     */
    public function getChildren(): ?Collection
    {
        return $this->children;
    }

    /**
     * @param Collection $children
     */
    public function setChildren(Collection $children): void
    {
        $this->children = $children;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    /**
     * @param \DateTimeInterface $birthDate
     */
    public function setBirthDate(\DateTimeInterface $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return string
     */
    public function getSex(): ?string
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
     * @return string|null
     */
    public function getShirtSize(): ?string {
        return $this->shirtSize;
    }

    /**
     * @param string $shirtSize
     */
    public function setShirtSize(string $shirtSize): void {
        $this->shirtSize = $shirtSize;
    }

    /**
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void  {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPhone(): ?string {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return bool
     */
    public function isCtuMember(): bool
    {
        return $this->ctuMember;
    }

    /**
     * @param bool $ctuMember
     */
    public function setCtuMember(bool $ctuMember): void
    {
        $this->ctuMember = $ctuMember;
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
     * @return array
     */
    public function getRoles(): array {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return Collection
     */
    public function getApplications(): ?Collection
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
     * @throws NonUniqueResultException
     */
    public function loadUserByUsername(string $email): ?Person {
        $entityManager =  $this->getEntityManager();
        return $entityManager->createQuery(
                'SELECT u
                FROM App\Entity\Person u
                WHERE u.email = :email'
            )
            ->setParameter('email', $email)
            ->getOneOrNullResult();
    }

}
