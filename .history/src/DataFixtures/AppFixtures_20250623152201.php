<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName('ingredient' . $i)
                ->setPrice(1.0 + $i);
            $manager->persist($ingredient);
        }
       $ingredient = new Ingredient();
       $ingredient->setName('ingredient1')
           ->setPrice(3.0);
           $manager->persist($ingredient);
        
        $manager->flush();
    }
}
