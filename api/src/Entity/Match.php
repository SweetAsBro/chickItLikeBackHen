<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The most generic type of item.
 *
 * @ORM\Entity
 * @ApiResource
 */
class Match
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
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Team",inversedBy="matchesHome")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull
     */
    private $homeTeam;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Team",inversedBy="matchesAway")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull
     */
    private $awayTeam;

    /**
     * @var \DateTimeInterface
     *
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     * @Assert\NotNull
     */
    private $startDate;

    /**
     * @var Group|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Group",inversedBy="matches")
     */
    private $group;

    /**
     * @var MatchDay|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\MatchDay",inversedBy="matches")
     */
    private $matchDay;

    /**
     * @var int|null
     * @ORM\Column(type="integer",nullable=true)
     */
    private $goalsHome;


    /**
     * @var int|null
     * @ORM\Column(type="integer",nullable=true)
     */
    private $goalsAway;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setHomeTeam(Team $homeTeam): void
    {
        $this->homeTeam = $homeTeam;
    }

    public function getHomeTeam(): Team
    {
        return $this->homeTeam;
    }

    public function setAwayTeam(Team $awayTeam): void
    {
        $this->awayTeam = $awayTeam;
    }

    public function getAwayTeam(): Team
    {
        return $this->awayTeam;
    }

    public function setStartDate(\DateTimeInterface $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getStartDate(): \DateTimeInterface
    {
        return $this->startDate;
    }

    public function setGroup(?Group $group): void
    {
        $this->group = $group;
    }

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setMatchDay(?MatchDay $matchDay): void
    {
        $this->matchDay = $matchDay;
    }

    public function getMatchDay(): ?MatchDay
    {
        return $this->matchDay;
    }

    public function getName(): string
    {
        return $this->homeTeam->getIdentifier() . " : " . $this->awayTeam->getIdentifier();
    }

    public function getGoalsHome(): ?int
    {
        return $this->goalsHome;
    }

    public function setGoalsHome(int $goalsHome): void
    {
        $this->goalsHome = $goalsHome;
    }

    public function getGoalsAway(): ?int
    {
        return $this->goalsAway;
    }

    public function setGoalsAway(int $goalsAway): void
    {
        $this->goalsAway = $goalsAway;
    }


}
