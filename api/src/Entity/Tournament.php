<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * The most generic type of item.
 *
 * @see http://schema.org/Thing Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource
 */
class Tournament
{
    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null the name of the item
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/name")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Group",mappedBy="tournament")
     * @ORM\JoinColumn(referencedColumnName="tournament_id")
     */
    private $groups;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MatchDay",mappedBy="tournament")
     * @ORM\JoinColumn(referencedColumnName="tournament_id")
     */
    private $matchDays;

    public function __construct()
    {
        $this->groups = [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getGroups()
    {
        return $this->groups;
    }

    public function setGroups(array $groups): void
    {
        $this->groups = $groups;
    }

    public function getMatchDays()
    {
        return $this->matchDays;
    }

    public function setMatchDays(array $matchDays): void
    {
        $this->matchDays = $matchDays;
    }



}
