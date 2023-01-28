<?php

namespace App\Entity;

use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Serializer\Filter\GroupFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\ApplicationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Unique;
#[ApiResource(
    operations: [
        new Get(),
        new Put(security: 'is_granted(\'ROLE_ADD_APPLICATION\')'),
        new Patch(security: 'is_granted(\'ROLE_EDIT_APPLICATION\')'),
        new Delete(security: 'is_granted(\'ROLE_DELETE_APPLICATION\')'),
        new GetCollection(security: 'is_granted(\'ROLE_VIEW_APPLICATION\')')
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']])]
#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
#[UniqueEntity("hash")]
#[ApiFilter(filterClass: SearchFilter::class, properties: ['hash' => 'exact', 'event' => 'exact', 'person' => 'exact'])]
#[ApiFilter(filterClass: DateFilter::class, properties: ['sign_date'])]
#[ApiFilter(
    filterClass: GroupFilter::class,
    arguments: ['parameterName' => 'groups', 'whitelist' => ['event', 'person', 'child', 'person.roles']])]
#[ApiResource(
    uriTemplate: '/applications/{hash}/person/applications.{_format}',
    operations: [new GetCollection()],
    uriVariables: [
        'hash' => new Link(fromClass: self::class, identifiers: ['hash']),
        'person' => new Link(fromClass: \App\Entity\Person::class, identifiers: [], expandedValue: 'person')
    ],
    status: 200,
    normalizationContext: ['groups' => ['read']],
    filters: [
        'annotated_app_entity_application_api_platform_core_bridge_doctrine_orm_filter_search_filter',
        'annotated_app_entity_application_api_platform_core_bridge_doctrine_orm_filter_date_filter',
        'annotated_app_entity_application_api_platform_serializer_filter_group_filter'
    ])]
#[ApiResource(
    uriTemplate: '/children/{id}/applications.{_format}',
    operations: [new GetCollection()],
    uriVariables: ['id' => new Link(fromClass: \App\Entity\Child::class, identifiers: ['id'])],
    status: 200,
    normalizationContext: ['groups' => ['read']],
    filters: [
        'annotated_app_entity_application_api_platform_core_bridge_doctrine_orm_filter_search_filter',
        'annotated_app_entity_application_api_platform_core_bridge_doctrine_orm_filter_date_filter',
        'annotated_app_entity_application_api_platform_serializer_filter_group_filter'])]
#[ApiResource(
    uriTemplate: '/people/{id}/applications.{_format}',
    operations: [new GetCollection()],
    uriVariables: ['id' => new Link(fromClass: \App\Entity\Person::class, identifiers: ['id'])],
    status: 200,
    normalizationContext: ['groups' => ['read']],
    filters: [
        'annotated_app_entity_application_api_platform_core_bridge_doctrine_orm_filter_search_filter',
        'annotated_app_entity_application_api_platform_core_bridge_doctrine_orm_filter_date_filter',
        'annotated_app_entity_application_api_platform_serializer_filter_group_filter'])]
class Application
{
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
    #[Groups(["event"])]
    private Event $event;
    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: true)]
    #[MaxDepth(2)]
    #[Groups(["person", "person.roles"])]
    private ?Person $person;
    #[ORM\ManyToOne(targetEntity: Child::class, inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: true)]
    #[MaxDepth(1)]
    #[Groups(["child"])]
    private ?Child $child;
    #[ORM\Column(type: 'datetime')]
    #[Groups(["read"])]
    private \DateTimeInterface $signDate;
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read", "write"])]
    private string $shirtSize;
    public function getPerson() : ?Person
    {
        return $this->person;
    }
    public function setPerson(Person $person) : self
    {
        $this->person = $person;
        return $this;
    }
    /**
     * @return Child
     */
    public function getChild() : ?Child
    {
        return $this->child;
    }
    /**
     * @param Child $child
     */
    public function setChild(Child $child) : self
    {
        $this->child = $child;
        return $this;
    }
    public function getSignDate() : ?\DateTimeInterface
    {
        return $this->signDate;
    }
    public function setSignDate(\DateTimeInterface $sign_date) : self
    {
        $this->signDate = $sign_date;
        return $this;
    }
    public function getHash() : string
    {
        return $this->hash;
    }
    public function setHash(string $hash) : self
    {
        $this->hash = $hash;
        return $this;
    }
    public function getShirtSize() : ?string
    {
        return $this->shirtSize;
    }
    public function setShirtSize(string $shirt_size) : self
    {
        $this->shirtSize = $shirt_size;
        return $this;
    }
    /**
     * @return Event
     */
    public function getEvent() : Event
    {
        return $this->event;
    }
    /**
     * @param Event $event
     */
    public function setEvent(Event $event) : self
    {
        $this->event = $event;
        return $this;
    }
}
