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
use App\Repository\ChildRepository;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Constraints\NotBlank;
#[ApiResource(operations: [new Get(security: 'is_granted(\'ROLE_VIEW_CHILDREN\')'), new Put(security: 'is_granted(\'ROLE_ADD_CHILDREN\')'), new Patch(security: 'is_granted(\'ROLE_EDIT_CHILDREN\')'), new GetCollection(security: 'is_granted(\'ROLE_VIEW_CHILDREN\')')], denormalizationContext: ['groups' => ['write']], normalizationContext: ['groups' => ['read'], 'enable_max_depth' => true])]
#[ORM\Entity(repositoryClass: ChildRepository::class)]
#[ApiFilter(filterClass: BooleanFilter::class, properties: ['active', 'ctu_member', 'deleted'])]
#[ApiFilter(filterClass: SearchFilter::class, properties: ['name' => 'partial', 'surname' => 'partial', 'parent' => 'exact', 'address' => 'exact'])]
#[ApiFilter(filterClass: DateFilter::class, properties: ['birth_date'])]
#[ApiFilter(filterClass: GroupFilter::class, arguments: ['parameterName' => 'groups', 'whitelist' => ['parent', 'applications', 'address']])]
#[ApiResource(uriTemplate: '/applications/{hash}/person/childrens.{_format}', uriVariables: ['hash' => new Link(fromClass: \App\Entity\Application::class, identifiers: ['hash']), 'person' => new Link(fromClass: \App\Entity\Person::class, identifiers: [], expandedValue: 'person')], status: 200, filters: ['annotated_app_entity_child_api_platform_core_bridge_doctrine_orm_filter_boolean_filter', 'annotated_app_entity_child_api_platform_core_bridge_doctrine_orm_filter_search_filter', 'annotated_app_entity_child_api_platform_core_bridge_doctrine_orm_filter_date_filter', 'annotated_app_entity_child_api_platform_serializer_filter_group_filter'], normalizationContext: ['groups' => ['read']], operations: [new GetCollection()])]
#[ApiResource(uriTemplate: '/applications/{hash}/child.{_format}', uriVariables: ['hash' => new Link(fromClass: \App\Entity\Application::class, identifiers: ['hash'])], status: 200, filters: ['annotated_app_entity_child_api_platform_core_bridge_doctrine_orm_filter_boolean_filter', 'annotated_app_entity_child_api_platform_core_bridge_doctrine_orm_filter_search_filter', 'annotated_app_entity_child_api_platform_core_bridge_doctrine_orm_filter_date_filter', 'annotated_app_entity_child_api_platform_serializer_filter_group_filter'], normalizationContext: ['groups' => ['read']], operations: [new Get()])]
#[ApiResource(uriTemplate: '/people/{id}/childrens.{_format}', uriVariables: ['id' => new Link(fromClass: \App\Entity\Person::class, identifiers: ['id'])], status: 200, filters: ['annotated_app_entity_child_api_platform_core_bridge_doctrine_orm_filter_boolean_filter', 'annotated_app_entity_child_api_platform_core_bridge_doctrine_orm_filter_search_filter', 'annotated_app_entity_child_api_platform_core_bridge_doctrine_orm_filter_date_filter', 'annotated_app_entity_child_api_platform_serializer_filter_group_filter'], normalizationContext: ['groups' => ['read']], operations: [new GetCollection()])]
class Child extends EntityRepository
{
    #[ApiProperty(identifier: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
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
    #[ORM\ManyToOne(targetEntity: Person::class, fetch: "EAGER", inversedBy: 'children')]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(1)]
    #[Groups(["parent"])]
    #[NotBlank]
    private Person $parent;
    #[ORM\ManyToOne(targetEntity: Address::class, fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(1)]
    #[Groups(["address"])]
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
    #[Groups(["applications"])]
    private ?Collection $applications;
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
    public function getName() : string
    {
        return $this->name;
    }
    /**
     * @param string $name
     */
    public function setName(string $name) : void
    {
        $this->name = $name;
    }
    /**
     * @return string
     */
    public function getSurname() : string
    {
        return $this->surname;
    }
    /**
     * @param string $surname
     */
    public function setSurname(string $surname) : void
    {
        $this->surname = $surname;
    }
    /**
     * @return string
     */
    public function getFullname() : string
    {
        return $this->name . " " . $this->surname;
    }
    /**
     * @return Person
     */
    public function getParent() : Person
    {
        return $this->parent;
    }
    /**
     * @param Person $parent
     */
    public function setParent(Person $parent) : void
    {
        $this->parent = $parent;
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
    /**
     * @return \DateTimeInterface
     */
    public function getBirthDate() : \DateTimeInterface
    {
        return $this->birthDate;
    }
    /**
     * @param \DateTimeInterface $birthDate
     */
    public function setBirthDate(\DateTimeInterface $birthDate) : void
    {
        $this->birthDate = $birthDate;
    }
    /**
     * @return string
     */
    public function getSex() : string
    {
        return $this->sex;
    }
    /**
     * @param string $sex
     */
    public function setSex(string $sex) : void
    {
        $this->sex = $sex;
    }
    /**
     * @return string
     */
    public function getShirtSize() : string
    {
        return $this->shirtSize;
    }
    /**
     * @param string $shirtSize
     */
    public function setShirtSize(string $shirtSize) : void
    {
        $this->shirtSize = $shirtSize;
    }
    /**
     * @return bool
     */
    public function isCtuMember() : bool
    {
        return $this->ctuMember;
    }
    /**
     * @param bool $ctuMember
     */
    public function setCtuMember(bool $ctuMember) : void
    {
        $this->ctuMember = $ctuMember;
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
     * @return Collection
     */
    public function getApplications() : Collection
    {
        return $this->applications;
    }
    /**
     * @param Collection $applications
     */
    public function setApplications(Collection $applications) : void
    {
        $this->applications = $applications;
    }
    public function getAgeAtDate(DateTime $date) : int
    {
        return $this->birthDate->diff($date)->y;
    }
}