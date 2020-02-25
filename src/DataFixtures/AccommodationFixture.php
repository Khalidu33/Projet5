<?php

namespace App\DataFixtures;

use App\Entity\Accommodation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AccommodationFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 100; $i++)
        {
            $accommodantion = new Accommodation();

            $accommodantion
                ->setTitle($faker->words(3, true))
                ->setDescription($faker->sentences(3, true))
                ->setContact($faker->sentence(3, true));

            $manager->persist($accommodantion);
        }
        $manager->flush();
    }
}
