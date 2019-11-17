<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Commande;
use App\Entity\Client;
use App\Entity\Categorie;
use App\Entity\Produit;
use App\Entity\LigneCommande;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        //Creation de 5 clients fakés
        $clients=array();
        for ($l=0; $l < 5; $l++) { 
            $client = new Client();
            $client->setNom($faker->name)
                    ->setAdresse($faker->address);
            array_push($clients,$client);
            $manager->persist($client);
        }
        //print_r($clients);

        //Creation de 3 categories fakés
        $produits=array();
        for ($i=0; $i < 3; $i++) { 
            $categorie = new Categorie();
            $categorie->setLibelle($faker->word);
            $manager->persist($categorie);

            //Creation de 10 produits fakés
            for ($j=0; $j < 10 ; $j++) { 
                $produit = new Produit();
                $produit->setLibelle($faker->word)
                        ->setQte($faker->numberBetween(1,20))
                        ->setPu($faker->randomFloat(100.00,49999.99))
                        ->setCategorie($categorie);
                array_push($produits,$produit);
                $manager->persist($produit);
            }
        }

        //Creation de 12 commandes
        for ($k=0; $k < 12 ; $k++) {
            $commande = new Commande();
            $commande->setClient($clients[mt_rand(0,4)])
                     ->setDateCom($faker->dateTime($max = 'now', $timezone = null));
            $manager->persist($commande);

            //Creation des lignes de commandes
            for ($t=0; $t < mt_rand(1,8) ; $t++) { 
               $ligne_commande = new LigneCommande();
               $ligne_commande->setQteCom(mt_rand(1,15))
                              ->setProduit($produits[mt_rand(0,9)])
                              ->setCommande($commande);
               $manager->persist($ligne_commande);
            }   
        }
        $manager->flush();
    }
}
