<?php
/**
 * Created by PhpStorm.
 * User: she
 * Date: 01.05.18
 * Time: 18:12
 */

namespace App\DataFixtures;


use App\Entity\Group;
use App\Entity\Match;
use App\Entity\MatchDay;
use App\Entity\Team;
use App\Entity\Tournament;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $tournaments = json_decode(file_get_contents(__DIR__."/../../data/fixtures/tournaments.json"));
        foreach ($tournaments as $fTournament){

            $tournament = new Tournament();
            $tournament->setName($fTournament->name);
            $manager->persist($tournament);

            foreach ($fTournament->groups as $fGroup){
                $group = new Group();
                $group->setName($fGroup->name);
                $group->setTournament($tournament);

                foreach ($fGroup->teams as $fTeam){
                    $team = new Team();
                    $team->setName($fTeam->name);
                    $team->setIdentifier($fTeam->identifier);
                    $group->addTeam($team);
                    $manager->persist($team);
                }
                $manager->persist($group);
            }

            $manager->flush();

            foreach ($fTournament->matchdays as $fMatchday) {
                $matchDay = new MatchDay();
                $matchDay->setName($fMatchday->name);
                $matchDay->setTournament($tournament);

                foreach ($fMatchday->groups as $fGroup){
                    $group = $manager->getRepository("App\Entity\Group")->findOneBy(["name"=>$fGroup->group]);

                    foreach ($fGroup->matches as $fMatch) {
                        $match = new Match();
                        $home = $manager->getRepository("App\Entity\Team")->findOneBy(["identifier"=>$fMatch->home]);
                        $away = $manager->getRepository("App\Entity\Team")->findOneBy(["identifier"=>$fMatch->away]);
                        $match->setHomeTeam($home);
                        $match->setAwayTeam($away);
                        $match->setGroup($group);
                        $match->setMatchDay($matchDay);
                        $match->setStartDate(new \DateTime($fMatch->start));
                        $manager->persist($match);
                    }

                }
                $manager->persist($matchDay);

            }
        }


        $manager->flush();
    }

}