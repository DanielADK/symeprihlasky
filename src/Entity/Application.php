<?php

namespace App\Entity;

use App\Repository\ApplicationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
class Application
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    private $person;

    #[ORM\Column(type: 'datetime')]
    private $sign_date;

    #[ORM\Column(type: 'string', length: 255)]
    private $hash;

    #[ORM\Column(type: 'string', length: 255)]
    private $shirt_size;

    public function getId(): ?int
    {
        return $this->id;
    }

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
        return $this->shirt_size;
    }

    public function setShirtSize(string $shirt_size): self
    {
        $this->shirt_size = $shirt_size;

        return $this;
    }
}
