<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\GroupList;

/**
 * The most generic type of item.
 *
 * @see http://schema.org/Thing Documentation on Schema.org
 *
 * @ORM\Entity(repositoryClass="GroupRepository")
 * @ORM\Table(name="tournamentGroup")
 * @ApiResource(itemOperations={
 *     "get",
 *     "list"={
 *         "method"="GET",
 *         "path"="/groups/{id}/list",
 *         "controller"=GroupList::class
 *     }
 * })
 */
class Group
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Tournament",inversedBy="groups")
     * @ORM\JoinColumn(name="tournament_id", nullable=false)
     * @Assert\NotNull
     */
    private $tournament;

    /**
     * @var \Doctrine\Common\Collections\Collection|Team
     *
     * @ORM\ManyToMany(targetEntity="Team",inversedBy="groups")
     * @ORM\JoinTable(name="teams_groups",
     *      joinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="team_id", referencedColumnName="id")}
    )
     *
     */
    private $teams;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Match",mappedBy="group")
     * @ORM\JoinColumn(referencedColumnName="group_id")
     */
    private $matches;

    private $listing;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
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
    public function setTeams(array $teams): void
    {
        $this->teams = $teams;
    }

    public function getTeams()
    {
        return $this->teams;
    }

    public function addTeam(Team $team) : void
    {
        if($this->teams->contains($team)){
            return;
        }
        $this->teams->add($team);
        $team->addGroup($this);
    }

    public function getMatches()
    {
        return $this->matches;
    }

    public function setMatches(array $matches): void
    {
        $this->matches = $matches;
    }

    public function getListing(){
        $listing = [];
        foreach($this->teams as $team){
            $listing[$team->getIdentifier()] = [
                "points" => 0,
                "goalsScored" => 0,
                "goalsReceived" => 0
            ];
        }
        foreach($this->matches as $match){
            $homeTeam = $match->getHomeTeam()->getIdentifier();
            $awayTeam = $match->getAwayTeam()->getIdentifier();
            $listing[$homeTeam]["goalsScored"] = $match->getGoalsHome();
            $listing[$homeTeam]["goalsReceived"] = $match->getGoalsAway();
            $listing[$awayTeam]["goalsScored"] = $match->getGoalsAway();
            $listing[$awayTeam]["goalsReceived"] = $match->getGoalsHome();

            if($match->getGoalsHome() > $match->getGoalsAway()) {
                $listing[$homeTeam]["points"] += 3;
            }
            else if($match->getGoalsAway() > $match->getGoalsHome()){
                $listing[$awayTeam]["points"] += 3;
            }
            else{
                $listing[$homeTeam]["points"] += 1;
                $listing[$awayTeam]["points"] += 1;
            }

        }
        uasort($listing,function($a,$b){
            if($a["points"] > $b["points"]){
                return -1;
            }
            if($a["points"] < $b["points"]){
                return 1;
            }
            else{
                $diffA = $a["goalsScored"] - $a["goalsReceived"];
                $diffB = $b["goalsScored"] - $b["goalsReceived"];
                if($diffA > $diffB){
                    return -1;
                }
                else if($diffA < $diffB){
                    return 1;
                }
                else{
                    if($a["goalsScored"] > $b["goalsScored"]){
                        return -1;
                    }
                    else if($a["goalsScored"] > $b["goalsScored"]){
                        return 1;
                    }
                    else{
                        return 0;
                    }
                }
            }
        });
        return $listing;
    }
}
