<?php

namespace App\DataFixtures;

use App\Entity\Advert;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setPassword($this->encoder->encodePassword($user, "dgsdg"));
        $user->setEmail("user@fixture");
        $user->setRoles(["ROLE_USER"]);
        $manager->persist($user);

        $admin = new User();
        $admin->setPassword($this->encoder->encodePassword($admin, "dgsdg"));
        $admin->setEmail("admin@fixture");
        $admin->setRoles(["ROLE_ADMIN"]);
        $manager->persist($admin);

        $categorieNames = ["XC", "Enduro", "All mountain", "Trail"];
        $categories = [];
        foreach ($categorieNames as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
            $categories[] = $category;
        }

        $manager->flush();

        $advert1 = new Advert();
        $advert1->setUser($user);
        $advert1->setTitle("advert 1");
        $advert1->setBikeYear(2020);
        $advert1->setCategory($categories[0]);
        $advert1->setDescription("Description");
        $advert1->setPrice(1000);
        $manager->persist($advert1);

        $advert2 = new Advert();
        $advert2->setUser($admin);
        $advert2->setTitle("advert 2");
        $advert2->setBikeYear(2021);
        $advert2->setCategory($categories[1]);
        $advert2->setDescription("Description 2");
        $advert2->setPrice(2000);
        $manager->persist($advert2);
//
//
        $manager->flush();
    }
}
