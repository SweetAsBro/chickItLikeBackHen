<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * The most generic type of item.
 *
 *
 * @ORM\Entity
 * @ApiResource
 */
class Team
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
     * @var string|null The identifier property represents any kind of identifier for any kind of \[\[Thing\]\], such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated properties for representing many of these, either as textual strings or as URL (URI) links. See \[background notes\](/docs/datamodel.html#identifierBg) for more details.
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/identifier")
     */
    private $identifier;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Match",mappedBy="homeTeam")
     * @ORM\JoinColumn(referencedColumnName="home_team_id")
     */
    private $matchesHome;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Match",mappedBy="awayTeam")
     * @ORM\JoinColumn(referencedColumnName="away_team_id")
     */
    private $matchesAway;

    /**
     * @var \Doctrine\Common\Collections\Collection|Group
     *
     * @ORM\ManyToMany(targetEntity="Group",inversedBy="teams")
     *
     */
    private $groups;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
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

    public function setIdentifier(?string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function getMatchesHome()
    {
        return $this->matchesHome;
    }

    public function setMatchesHome(array $matches): void
    {
        $this->matchesHome = $matches;
    }

    public function getMatchesAway()
    {
        return $this->matchesAway;
    }

    public function setMatchesAway(array $matchesAway): void
    {
        $this->matchesAway = $matchesAway;
    }

    public function setGroups(array $groups): void
    {
        $this->groups = $groups;
    }

    public function getGroups()
    {
        return $this->groups;
    }

    public function addGroup(Group $group){
        if($this->groups->contains($group)){
            return;
        }
        $this->groups->add($group);
        $group->addTeam($this);
    }

}
