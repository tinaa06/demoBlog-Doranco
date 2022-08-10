<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
       for ($i=1; $i <= 10; $i++) { 
        $article = new Article;

        $article->setTitle("Titre de l'article n°$i")
                ->setContent("<p>Contenu de l'article</p>")
                ->setImage("http://picsum.photos/250/150")
                ->setCreatedAt(new \DateTime()); //on instancie la classe dateTime pour récupere  
        $manager->persist($article);
        // persist () permet de faire persister l'article dans le temps et prépare so, insertion en bdd
       }


        $manager->flush();
    }
}
