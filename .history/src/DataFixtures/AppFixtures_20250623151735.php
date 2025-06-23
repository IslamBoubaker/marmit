<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
       $ingerdient = new Ingredient
       $ingredient ->setName('ingredient1')
           ->setPrice(1.50)
           ->setCreatedAt(new \DateTimeImmutable());
        $manager->flush();
    }
}
