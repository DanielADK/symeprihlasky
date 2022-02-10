<?php

namespace App\Entity;

use App\Repository\ChildRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\NonUniqueResultException;

#[ORM\Entity(repositoryClass: ChildRepository::class)]
class Child extends EntityRepository {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $surname;

    #[ORM\ManyToOne(targetEntity: Address::class, inversedBy: 'people')]
    #[ORM\JoinColumn(nullable: false)]
    private Address $address;

    #[ORM\Column(type: 'date', nullable: true)]
    private \DateTimeInterface $birth_date;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $sex;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $shirt_size;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $email;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private bool $ctu_member;

    #[ORM\OneToMany(mappedBy: 'person', targetEntity: Application::class)]
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


}