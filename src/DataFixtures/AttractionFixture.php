<?php

namespace App\DataFixtures;

use App\Entity\Attraction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AttractionFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 100; $i++)
        {
            $attraction = new Attraction();

            $attraction
                ->setTitle($faker->words(3, true))
                ->setDescription($faker->sentences(3, true))
                ->setAge($faker->numberBetween(0, 80))
                ->setSize($faker->numberBetween(60, 200))
                ->setSeason($faker->randomElement($array = array ('Hiver','Été','Été et Hiver')));

            $manager->persist($attraction);
        }
        $manager->flush();
    }
}
