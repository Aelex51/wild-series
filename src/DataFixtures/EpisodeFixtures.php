<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    private $episodeDuration = 0;

    private SluggerInterface $slugger;

    public const NBOFEPISODES = 10;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($j=0; $j<SeasonFixtures::$numSeason; $j++) {
            for ($i = 0; $i < 10; $i++) {
                $episode = new Episode();
                $episode->setTitle($faker->sentence(4));
                $episode->setNumber($i+1);
                $episode->setSynopsis($faker->paragraph());
                $episode->setSeason($this->getReference('season_' . $j));
                $episode->setDuration($faker->numberBetween(40,60));
                $episode->setSlug($this->slugger->slug($episode->getTitle()));
                $manager->persist($episode);
            }
        }
        
        $manager->flush();
    }
        // $faker = Factory::create();

        // for($index = 0; $index < SeasonFixtures::$numSeason; $index++) {

        //     for($i = 0; $i < self::NBOFEPISODES; $i++) {
        //         $episode = new Episode();
            
        //         $episode->setNumber($i+1);
        //         $episode->setTitle($faker->word());
        //         $episode->setSynopsis($faker->paragraphs(3, true));

        //         $episode->setSeason($this->getReference('season_' . $index));

        //         $manager->persist($episode);
        //     }
        // }
        // $manager->flush();


    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          SeasonFixtures::class,
        ];
    }
}
