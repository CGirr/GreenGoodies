<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $products = [
            ['name' => 'Gourde en bambou', 'image' => 'gourde.jpg'],
            ['name' => 'Sac en coton bio', 'image' => 'sac.jpg'],
            ['name' => 'Brosse à dents en bois', 'image' => 'brosse.jpg'],
            ['name' => 'Savon solide artisanal', 'image' => 'savon.jpg'],
            ['name' => 'Bee wrap', 'image' => 'beewrap.jpg'],
            ['name' => 'Paille en inox', 'image' => 'paille.jpg'],
        ];

        foreach ($products as $data) {
            $product = new Product();
            $product->setName($data['name']);
            $product->setPicture($data['image']);
            $product->setShortDescription($faker->sentence());
            $product->setFullDescription($faker->paragraph(3));
            $product->setPrice($faker->randomFloat(2, 5, 50));

            $manager->persist($product);
        }

        $manager->flush();
    }
}
