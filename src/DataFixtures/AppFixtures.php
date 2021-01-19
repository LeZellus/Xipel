<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\Article;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // On configure dans quelles langues nous voulons nos données
        $faker = Faker\Factory::create('fr_FR');
        $roles = [];

        for ($i = 0; $i < 10; $i++) {
            // Article fake data
            $article = new Article();
            $article->setThumb("https://i.pinimg.com/originals/fe/78/bb/fe78bbb25f35d56b502327fb6d43b309.png");
            $article->setTitle($faker->sentence($nbWords = 6, $variableNbWords = true));
            $article->setContent($faker->text($maxNbChars = 200));
            $article->setIsPublished($faker->boolean($chanceOfGettingTrue = 80));
            $article->setPublishedAt($faker->dateTimeAD($max = 'now', $timezone = null));
            $article->setUpdatedAt($faker->dateTimeAD($max = 'now', $timezone = null));
            $manager->persist($article);
        }

        // User fake data
        $user = new User();
        $user->setUsername("LeZellus");
        $user->setPassword(password_hash ( "Playmate12" , PASSWORD_BCRYPT));
        $user->setRoles(["matheo.zeller@gmail.com", "ROLE_ADMIN"]);
        $user->setEmail("matheo.zeller@gmail.com");
        $manager->persist($user);

        $manager->flush();

        // Category() fake data

        for ($i = 0; $i < 10; $i++) {
            // Article fake data
            $user = new Category();
            $user->setColor($faker->hexColor);
            $user->setLabel($faker->word);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
