<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public static int $numPrograms = 0;
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public const PROGRAMLIST = [
        [
            "title" => "Breaking Bad",
            "synopsis" => "La série se concentre sur Walter White, un professeur de chimie surqualifié et père de famille, qui, ayant appris qu'il est atteint d'un cancer du poumon en phase terminale, sombre dans le crime pour assurer l'avenir financier de sa famille.",
            "category" => "category_Drame"
        ],
        [
            "title" => "The Mandalorian",
            "synopsis" => "Après les aventures de Jango et Boba Fett, un nouveau héros émerge dans l'univers Star Wars",
            "category" => "category_Aventure"
        ],
        [
            "title" => "His dark materials",
            "synopsis" => "Courageuse et futée, Lyra se retrouve embarquée dans une folle aventure dans les contrées du Nord, à la recherche de son meilleur ami disparu. Pourquoi cette jeune fille orpheline, élevée dans l'atmosphère austère et confinée du prestigieux Jordan College, fait-elle l'objet de tant d'attentions ?",
            "category" => "category_Fantastique"
        ],
        [
            "title" => "The Witcher",
            "synopsis" => "Inspiré d'une série littéraire fantastique à succès, The Witcher est un récit épique sur la famille et le destin. Geralt de Riv, un chasseur de monstres solitaire, se bat pour trouver sa place dans un monde où les humains sont souvent plus vicieux que les bêtes.",
            "category" => "category_Aventure"
        ],
        [
            "title" => "Mercredi",
            "synopsis" => "A présent étudiante à la singulière Nevermore Academy, Wednesday Addams tente de s'adapter auprès des autres élèves tout en enquêtant à la suite d'une série de meurtres qui terrorise la ville...",
            "category" => "category_Aventure"
        ],

        [
            "title" => "Vikings",
            "synopsis" => "Scandinavie, à la fin du 8ème siècle. Ragnar Lodbrok, un jeune guerrier viking, est avide d'aventures et de nouvelles conquêtes. Lassé des pillages sur les terres de l'Est, il se met en tête d'explorer l'Ouest par la mer.",
            "category" => "category_Action"
        ],

    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::PROGRAMLIST as $key => $programInfos) {
            $program = new Program();

            $program->setTitle($programInfos["title"]);
            $program->setSynopsis($programInfos["synopsis"]);
            $program->setCategory($this->getReference($programInfos["category"]));
            $program->setSlug($this->slugger->slug($program->getTitle()));
            $program->setOwner($this->getReference('user_'));
            $manager->persist($program);
            $this->addReference('program_' . $key, $program);
            self::$numPrograms++;
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            CategoryFixtures::class,
            UserFixtures::class
        ];
    }
}

