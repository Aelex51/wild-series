<?php

namespace App\DataFixtures;

use App\Entity\Program;
use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    // public const NBOFSEASONS = 5;
    public static int $numSeason = 0;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($j=0; $j<ProgramFixtures::$numPrograms; $j++) {
            for ($i = 0; $i < 5; $i++) {
                $season = new Season();
                //Ce Faker va nous permettre d'alimenter l'instance de Season que l'on souhaite ajouter en base
                $season->setNumber($i+1);
                $season->setYear($faker->year());
                $season->setDescription($faker->paragraphs(3, true));

                $season->setProgram($this->getReference('program_' . $j));
                $manager->persist($season);
                $this->addReference('season_' . self::$numSeason, $season);
                self::$numSeason++;
            }
        }
        

        $manager->flush();
        
           // for($index = 0; $index < count(ProgramFixtures::PROGRAMLIST); $index++) {

        //     for($i = 0; $i < self::NBOFSEASONS; $i++) {
        //         $season = new Season();
            
        //         $season->setNumber($i+1);
        //         $season->setYear($faker->year());
        //         $season->setDescription($faker->paragraphs(3, true));

        //         $season->setProgram($this->getReference('program_' . $index));
        //         $this->addReference('season_' . self::$numSeason, $season);
        //         $manager->persist($season);
        //         self::$numSeason++;
        //     }
        // }
        // $manager->flush();
     
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          ProgramFixtures::class,
        ];
    }
}