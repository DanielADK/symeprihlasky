<?php

namespace App\Entity;

use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Serializer\Filter\GroupFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints\NotBlank;
#[ApiResource(
    operations: [
        new Get(security: 'is_granted(\'ROLE_VIEW_ADDRESS\')'),
        new Put(security: 'is_granted(\'ROLE_ADD_ADDRESS\')'),
        new Patch(security: 'is_granted(\'ROLE_EDIT_ADDRESS\')'),
        new GetCollection(security: 'is_granted(\'ROLE_VIEW_ADDRESS\')')
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']])]
#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ORM\UniqueConstraint(name: "address", columns: ["street", "city", "postcode"])]
#[ApiFilter(
    filterClass: SearchFilter::class,
    properties: [
        'street' => 'partial',
        'city' => 'partial',
        'postcode' => 'exact',
        'people' => 'partial'
    ]
)]
#[ApiFilter(
    filterClass: GroupFilter::class,
    arguments: ['parameterName' => 'groups', 'whitelist' => ['people', 'events']]
)]
#[ApiResource(
    uriTemplate: '/applications/{hash}/event/address.{_format}',
    operations: [new Get()],
    uriVariables: [
        'hash' => new Link(fromClass: \App\Entity\Application::class, identifiers: ['hash']),
        'event' => new Link(fromClass: \App\Entity\Event::class, identifiers: [], expandedValue: 'event')
    ],
    status: 200,
    normalizationContext: ['groups' => ['read']],
    filters: [
        'annotated_app_entity_address_api_platform_core_bridge_doctrine_orm_filter_search_filter',
        'annotated_app_entity_address_api_platform_serializer_filter_group_filter'
    ]
)]
#[ApiResource(
    uriTemplate: '/applications/{hash}/person/address.{_format}',
    operations: [new Get()],
    uriVariables: [
        'hash' => new Link(fromClass: \App\Entity\Application::class, identifiers: ['hash']),
        'person' => new Link(fromClass: \App\Entity\Person::class, identifiers: [], expandedValue: 'person')
    ],
    status: 200,
    normalizationContext: ['groups' => ['read']],
    filters: [
        'annotated_app_entity_address_api_platform_core_bridge_doctrine_orm_filter_search_filter',
        'annotated_app_entity_address_api_platform_serializer_filter_group_filter']
)]
#[ApiResource(
    uriTemplate: '/events/{id}/address.{_format}',
    operations: [new Get()],
    uriVariables: [
        'id' => new Link(fromClass: \App\Entity\Event::class, identifiers: ['id'])
    ],
    status: 200,
    normalizationContext: ['groups' => ['read']],
    filters: [
        'annotated_app_entity_address_api_platform_core_bridge_doctrine_orm_filter_search_filter',
        'annotated_app_entity_address_api_platform_serializer_filter_group_filter'
    ]
)]
#[ApiResource(
    uriTemplate: '/children/{id}/address.{_format}',
    operations: [new Get()],
    uriVariables: ['id' => new Link(fromClass: \App\Entity\Child::class, identifiers: ['id'])],
    status: 200,
    normalizationContext: ['groups' => ['read']],
    filters: [
        'annotated_app_entity_address_api_platform_core_bridge_doctrine_orm_filter_search_filter',
        'annotated_app_entity_address_api_platform_serializer_filter_group_filter'
    ]
)]
#[ApiResource(
    uriTemplate: '/people/{id}/address.{_format}',
    operations: [new Get()],
    uriVariables: ['id' => new Link(fromClass: \App\Entity\Person::class, identifiers: ['id'])],
    status: 200,
    normalizationContext: ['groups' => ['read']],
    filters: [
        'annotated_app_entity_address_api_platform_core_bridge_doctrine_orm_filter_search_filter',
        'annotated_app_entity_address_api_platform_serializer_filter_group_filter']
)]
class Address
{
    #[ApiProperty(identifier: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["read"])]
    private int $id;
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private string $street;
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private string $city;
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private string $postcode;
    #[ORM\OneToMany(mappedBy: 'address', targetEntity: Person::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[MaxDepth(1)]
    #[Groups(["people"])]
    private ?Collection $people;
    #[ORM\OneToMany(mappedBy: 'address', targetEntity: Event::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[MaxDepth(1)]
    #[Groups(["events"])]
    private ?Collection $events;
    public function __construct(string $street, string $city, string $postcode)
    {
        $this->street = $street;
        $this->city = $city;
        $this->postcode = $postcode;
        $this->people = new ArrayCollection();
    }
    public function deepCopy(Address $address)
    {
        $this->street = $address->street;
        $this->city = $address->city;
        $this->postcode = $address->postcode;
    }
    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }
    /**
     * @param int $id
     */
    public function setId(int $id) : void
    {
        $this->id = $id;
    }
    /**
     * @return string
     */
    public function getStreet() : string
    {
        return $this->street;
    }
    /**
     * @param string $street
     */
    public function setStreet(string $street) : void
    {
        $this->street = $street;
    }
    /**
     * @return string
     */
    public function getCity() : string
    {
        return $this->city;
    }
    /**
     * @param string $city
     */
    public function setCity(string $city) : void
    {
        $this->city = $city;
    }
    /**
     * @return string
     */
    public function getPostcode() : string
    {
        return $this->postcode;
    }
    /**
     * @param string $postcode
     */
    public function setPostcode(string $postcode) : void
    {
        $this->postcode = $postcode;
    }
    /**
     * @return ArrayCollection|Collection
     */
    public function getPeople() : ArrayCollection|Collection
    {
        return $this->people;
    }
    /**
     * @param ArrayCollection|Collection $people
     */
    public function setPeople(ArrayCollection|Collection $people) : void
    {
        $this->people = $people;
    }
    /**
     * @return Collection|null
     */
    public function getEvents() : ?Collection
    {
        return $this->events;
    }
    /**
     * @param Collection|null $events
     */
    public function setEvents(?Collection $events) : void
    {
        $this->events = $events;
    }
    public function __toString() : string
    {
        return "{\"id\":{$this->id}, \"street\":{$this->street}, \"city\":{$this->city}, \"postcode\":{$this->postcode}}";
    }
    public static function cmp(Address &$a1, Address &$a2) : bool
    {
        return $a1->getStreet() == $a2->getStreet() && $a1->getCity() == $a2->getCity() && $a1->getPostcode() == $a2->getPostcode();
    }
}
