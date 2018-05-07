<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The most generic type of item.
 *
 * @see http://schema.org/Thing Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource
 */
class MatchDay
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
     * @var Tournament
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Tournament",inversedBy="matchDays")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull
     */
    private $tournament;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Match",mappedBy="matchDay")
     * @ORM\JoinColumn(referencedColumnName="match_day_id")
     */
    private $matches;


    public function __construct()
    {
        $this->matches = new ArrayCollection();
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

    public function setTournament(Tournament $tournament): void
    {
        $this->tournament = $tournament;
    }

    public function getTournament(): Tournament
    {
        return $this->tournament;
    }

    public function getMatches()
    {
        return $this->matches;
    }

    public function setMatches(array $matches): void
    {
        $this->matches = $matches;
    }

    public function addMatch(Match $match) : void
    {
        if($this->matches->contains($match)){
            return;
        }
        $this->matches->add($match);
        $match->setMatchDay($this);
    }

}
