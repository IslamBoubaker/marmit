<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for
       $ingredient = new Ingredient();
       $ingredient->setName('ingredient1')
           ->setPrice(3.0);
           $manager->persist($ingredient);
        
        $manager->flush();
    }
}
