<?php

namespace App\Entity;

use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Serializer\Filter\GroupFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\LogRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints\NotBlank;
#[ApiResource(operations: [new Get(security: 'is_granted(\'ROLE_VIEW_LOG\')'), new Put(security: 'is_granted(\'ROLE_ADD_LOG\')'), new GetCollection(security: 'is_granted(\'ROLE_VIEW_LOG\')')], normalizationContext: ['groups' => ['read'], 'enable_max_depth' => true])]
#[ORM\Entity(repositoryClass: LogRepository::class)]
#[ApiFilter(filterClass: DateFilter::class, properties: [dateTime::class])]
#[ApiFilter(filterClass: GroupFilter::class, arguments: ['parameterName' => 'groups', 'whitelist' => ['user']])]
#[ApiResource(uriTemplate: '/applications/{hash}/person/logs.{_format}', uriVariables: ['hash' => new Link(fromClass: \App\Entity\Application::class, identifiers: ['hash']), 'person' => new Link(fromClass: \App\Entity\Person::class, identifiers: [], expandedValue: 'person')], status: 200, filters: ['annotated_app_entity_log_api_platform_core_bridge_doctrine_orm_filter_date_filter', 'annotated_app_entity_log_api_platform_serializer_filter_group_filter'], normalizationContext: ['groups' => ['read']], operations: [new GetCollection()])]
#[ApiResource(uriTemplate: '/people/{id}/logs.{_format}', uriVariables: ['id' => new Link(fromClass: \App\Entity\Person::class, identifiers: ['id'])], status: 200, filters: ['annotated_app_entity_log_api_platform_core_bridge_doctrine_orm_filter_date_filter', 'annotated_app_entity_log_api_platform_serializer_filter_group_filter'], normalizationContext: ['groups' => ['read']], operations: [new GetCollection()])]
class Log
{
    #[ApiProperty(identifier: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["read"])]
    private int $id;
    #[ORM\Column(type: 'datetime', length: 255)]
    #[NotBlank]
    private \DateTimeInterface $dateTime;
    #[ORM\ManyToOne(targetEntity: Person::class, fetch: 'EAGER', inversedBy: 'logs')]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(1)]
    #[NotBlank]
    private Person $user;
    #[ORM\Column(type: 'string', length: 1023)]
    #[NotBlank]
    private string $content;
    /**
     * @param \DateTimeInterface $dateTime
     * @param Person $user
     * @param string $content
     */
    public function __construct(\DateTimeInterface $dateTime, Person $user, string $content)
    {
        $this->dateTime = $dateTime;
        $this->user = $user;
        $this->content = $content;
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
     * @return \DateTimeInterface
     */
    public function getDateTime() : \DateTimeInterface
    {
        return $this->dateTime;
    }
    /**
     * @param \DateTimeInterface $dateTime
     */
    public function setDateTime(\DateTimeInterface $dateTime) : void
    {
        $this->dateTime = $dateTime;
    }
    /**
     * @return Person
     */
    public function getUser() : Person
    {
        return $this->user;
    }
    /**
     * @param Person $user
     */
    public function setUser(Person $user) : void
    {
        $this->user = $user;
    }
    /**
     * @return string
     */
    public function getContent() : string
    {
        return $this->content;
    }
    /**
     * @param string $content
     */
    public function setContent(string $content) : void
    {
        $this->content = $content;
    }
}