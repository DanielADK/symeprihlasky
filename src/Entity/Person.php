<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method null loadUserByIdentifier(string $identifier)
 */
#[ORM\Entity(repositoryClass: PersonRepository::class)]
#[UniqueEntity("email")]
class Person implements UserInterface, PasswordAuthenticatedUserInterface {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $surname;

    #[ORM\ManyToOne(targetEntity: Address::class, inversedBy: 'people')]
    #[ORM\JoinColumn(nullable: true)]
    private Address $address;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: Child::class)]
    #[ORM\JoinColumn(nullable: true)]
    private Collection $children;

    #[ORM\Column(type: 'date', nullable: true)]
    private \DateTimeInterface $birth_date;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $sex;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $shirt_size;

    #[ORM\Column(type: 'string', length: 255, unique: true, nullable: false)]
    private string $email;

    #[ORM\Column(type: 'string', length: 255, unique: true, nullable: true)]
    private string $phone;

    #[ORM\Column(type: 'string', nullable: true)]
    private string $password;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private bool $ctu_member;

    #[ORM\Column(type: 'boolean', nullable: true, options: ["default" => true])]
    private bool $active;

    #[ORM\Column(type: 'json', nullable: true)]
    private array $roles = [];

    #[ORM\OneToMany(mappedBy: 'person', targetEntity: Application::class)]
    #[ORM\JoinColumn(nullable: true)]
    private Collection $applications;

    public function getUserIdentifier(): string {
        return $this->email;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birth_date;
    }

    public function setBirthDate(?\DateTimeInterface $birth_date): self
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(?string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getShirtSize(): ?string
    {
        return $this->shirt_size;
    }

    public function setShirtSize(?string $shirt_size): self
    {
        $this->shirt_size = $shirt_size;

        return $this;
    }

    public function getCtuMember(): ?bool
    {
        return $this->ctu_member;
    }

    public function setCtuMember(?bool $ctu_member): self
    {
        $this->ctu_member = $ctu_member;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
    public function getPassword(): string {
        return $this->password;
    }
    public function setPassword(string $password): self {
        $this->password = $password;
        return $this;
    }
    public function getRoles(): array {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function getSalt() {
        return null;
    }

    public function eraseCredentials() {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): string
    {
        return $this->name . '-' . $this->surname;
    }

    /**
     * @return Collection
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function addApplication(Application $application): self
    {
        if (!$this->applications->contains($application)) {
            $this->applications[] = $application;
            $application->setPerson($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): self
    {
        if ($this->applications->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getPerson() === $this) {
                $application->setPerson(null);
            }
        }

        return $this;
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
