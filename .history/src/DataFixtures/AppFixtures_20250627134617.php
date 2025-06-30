<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use phpDocumentor\Reflection\DocBlock\Tags\Generic;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture {

    private UserPasswordHasherInterface $hasher;
    private $faker;

    
    public function __construct(UserPasswordHasherInterface $hasher)
      {
        $this->faker = Factory::create( 'fr_FR' );
        $this->hasher = $hasher;
    }

    public function load( ObjectManager $manager ): void {
        for ( $i = 1; $i < 50 ; $i++ ) {

            $ingredient = new Ingredient();
            $ingredient->setName( $this->faker->word() );
            $ingredient->setPrice( mt_rand( 0, 100 ) );
            $ingredients[] = $ingredient;
            $manager->persist( $ingredient );
        }



        for ( $j = 1; $j < 25 ; $j++ ) {

            $recipe = new Recipe();
            $recipe-> setName( $this->faker->word() )
            ->setTime( mt_rand( 1, 1440 ) )
            ->setNbPeople( mt_rand( 0, 1 ) == 1? mt_rand( 1, 50 ):null )
            ->setDifficulty( mt_rand( 0, 1 ) == 1? mt_rand( 1, 5 ):null )
            ->setDescription( $this->faker->text( 300 ) )
            ->setPrice( mt_rand( 0, 1 ) == 1? mt_rand( 1, 1000 ):null )
            ->setIsFavorite( mt_rand( 0, 1 ) == 1? true:false );
            // ->setUser($users[mt_rand(0,count($users)-1)]);
    

            for ( $k = 0; $k < mt_rand( 5, 15 ) ; $k++ ) {
                $recipe->addIngredient( $ingredients[ mt_rand( 0, count( $ingredients )-1 ) ] );

                $users =[];
// $users = $manager->getRepository(User::class)->findAll();

for ( $c = 0; $c <10 ; $c++ ) {
    $user = new User();
    $user->setName( $this->faker->name() )
    ->setPseudo( mt_rand(0,1)===1 ? $this->faker->word():null )
    ->setEmail( $this->faker->email() )
    ->setRoles(['ROLE_USER'])
    ->setPlainPassword("password");
$users[]=$user;
    $manager->persist( $user );
}
$manager->flush();
                $manager->persist( $recipe );
            }

            $manager->flush();
        }
    
// USER

for ( $a = 0; $a <10 ; $a++ ) {
    $user = new User();
    $user->setName( $this->faker->name() )
    ->setPseudo( $this->faker->word() )
    ->setEmail( $this->faker->email() )
    ->setRoles(['ROLE_USER'])
    ->setPlainPassword("password")
    $recipe->setUser($users[mt_rand(0, count($users) - 1)]);

    ->setUser($users[mt_rand(0,count($users)-1)]);

    $manager->persist( $user );
}
$manager->flush();


// USERS
$users =[];
// $users = $manager->getRepository(User::class)->findAll();

for ( $c = 0; $c <10 ; $c++ ) {
    $user = new User();
    $user->setName( $this->faker->name() )
    ->setPseudo( mt_rand(0,1)===1 ? $this->faker->word():null )
    ->setEmail( $this->faker->email() )
    ->setRoles(['ROLE_USER'])
    ->setPlainPassword("password");
$users[]=$user;
    $manager->persist( $user );
}
$manager->flush();

}
}