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
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\EventRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints\NotBlank;
#[ApiResource(operations: [new Get(security: 'is_granted(\'ROLE_VIEW_EVENT\')'), new Put(security: 'is_granted(\'ROLE_ADD_EVENT\')'), new Patch(security: 'is_granted(\'ROLE_EDIT_EVENT\')'), new GetCollection(security: 'is_granted(\'ROLE_VIEW_EVENT\')')], denormalizationContext: ['groups' => ['write']], normalizationContext: ['groups' => ['read']])]
#[ORM\Entity(repositoryClass: EventRepository::class)]
#[UniqueEntity("shortName")]
#[ApiFilter(filterClass: BooleanFilter::class, properties: ['deleted'])]
#[ApiFilter(filterClass: SearchFilter::class, properties: ['name_short' => 'partial', 'type' => 'exact'])]
#[ApiFilter(filterClass: DateFilter::class, properties: ['dateStart', 'dateEnd'])]
#[ApiFilter(filterClass: GroupFilter::class, arguments: ['parameterName' => 'groups', 'whitelist' => ['address']])]
#[ApiResource(uriTemplate: '/addresses/{id}/events.{_format}', uriVariables: ['id' => new Link(fromClass: \App\Entity\Address::class, identifiers: ['id'])], status: 200, filters: ['annotated_app_entity_event_api_platform_core_bridge_doctrine_orm_filter_boolean_filter', 'annotated_app_entity_event_api_platform_core_bridge_doctrine_orm_filter_search_filter', 'annotated_app_entity_event_api_platform_core_bridge_doctrine_orm_filter_date_filter', 'annotated_app_entity_event_api_platform_serializer_filter_group_filter'], normalizationContext: ['groups' => ['read']], operations: [new GetCollection()])]
#[ApiResource(uriTemplate: '/applications/{hash}/event.{_format}', uriVariables: ['hash' => new Link(fromClass: \App\Entity\Application::class, identifiers: ['hash'])], status: 200, filters: ['annotated_app_entity_event_api_platform_core_bridge_doctrine_orm_filter_boolean_filter', 'annotated_app_entity_event_api_platform_core_bridge_doctrine_orm_filter_search_filter', 'annotated_app_entity_event_api_platform_core_bridge_doctrine_orm_filter_date_filter', 'annotated_app_entity_event_api_platform_serializer_filter_group_filter'], normalizationContext: ['groups' => ['read']], operations: [new Get()])]
class Event
{
    #[ApiProperty(identifier: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["read"])]
    private $id;
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private string $shortName;
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private string $fullName;
    #[ORM\Column(type: 'date')]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private \DateTimeInterface $dateStart;
    #[ORM\Column(type: 'date')]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private \DateTimeInterface $dateEnd;
    #[ORM\Column(type: 'string', length: 2)]
    #[Groups(["read", "write"])]
    #[NotBlank]
    private string $type;
    #[ORM\Column(type: 'boolean', options: ["default" => false])]
    #[Groups(["read", "write"])]
    private bool $activeApplication;
    #[ORM\Column(type: 'boolean', options: ["default" => false])]
    #[Groups(["write"])]
    private bool $deleted;
    #[ORM\Column(type: 'integer')]
    #[Groups(["read", "write"])]
    private int $priceMember;
    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(["read", "write"])]
    private int $priceOther;
    #[ORM\Column(type: 'integer', options: ["default" => -1])]
    #[Groups(["read", "write"])]
    private int $capacity;
    #[ORM\ManyToOne(targetEntity: Address::class, inversedBy: 'events')]
    #[ORM\JoinColumn(referencedColumnName: "id", nullable: false, columnDefinition: "INT NOT NULL DEFAULT 1")]
    #[MaxDepth(1)]
    #[Groups(["address"])]
    private Address $address;
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
    public function getshortName() : string
    {
        return $this->shortName;
    }
    /**
     * @param string $shortName
     */
    public function setshortName(string $shortName) : void
    {
        $this->shortName = $shortName;
    }
    /**
     * @return string
     */
    public function getfullName() : string
    {
        return $this->fullName;
    }
    /**
     * @param string $fullName
     */
    public function setfullName(string $fullName) : void
    {
        $this->fullName = $fullName;
    }
    /**
     * @return DateTimeInterface
     */
    public function getDateStart() : \DateTimeInterface
    {
        return $this->dateStart;
    }
    /**
     * @param DateTimeInterface $dateStart
     */
    public function setDateStart(DateTimeInterface $dateStart) : void
    {
        $this->dateStart = $dateStart;
    }
    /**
     * @return DateTimeInterface
     */
    public function getDateEnd() : DateTimeInterface
    {
        return $this->dateEnd;
    }
    /**
     * @param DateTimeInterface $dateEnd
     */
    public function setDateEnd(DateTimeInterface $dateEnd) : void
    {
        $this->dateEnd = $dateEnd;
    }
    /**
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }
    /**
     * @param string $type
     */
    public function setType(string $type) : void
    {
        $this->type = $type;
    }
    /**
     * @return bool
     */
    public function isActiveApplication() : bool
    {
        return $this->activeApplication;
    }
    /**
     * @param bool $activeApplication
     */
    public function setActiveApplication(bool $activeApplication) : void
    {
        $this->activeApplication = $activeApplication;
    }
    /**
     * @return int
     */
    public function getCapacity() : int
    {
        return $this->capacity;
    }
    /**
     * @param int $capacity
     */
    public function setCapacity(int $capacity) : void
    {
        $this->capacity = $capacity;
    }
    /**
     * @return bool
     */
    public function isDeleted() : bool
    {
        return $this->deleted;
    }
    /**
     * @param bool $deleted
     */
    public function setDeleted(bool $deleted) : void
    {
        $this->deleted = $deleted;
    }
    /**
     * @return int
     */
    public function getPriceMember() : int
    {
        return $this->priceMember;
    }
    /**
     * @param int $priceMember
     */
    public function setPriceMember(int $priceMember) : void
    {
        $this->priceMember = $priceMember;
    }
    /**
     * @return int
     */
    public function getPriceOther() : int
    {
        return $this->priceOther ?? -1;
    }
    /**
     * @param int $priceOther
     */
    public function setPriceOther(int $priceOther) : void
    {
        $this->priceOther = $priceOther;
    }
    /**
     * @return Address
     */
    public function getAddress() : Address
    {
        return $this->address;
    }
    /**
     * @param Address $address
     */
    public function setAddress(Address $address) : void
    {
        $this->address = $address;
    }
}
