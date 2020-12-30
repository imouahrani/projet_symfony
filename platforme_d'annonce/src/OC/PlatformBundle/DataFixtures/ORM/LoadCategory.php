<?php
/**
 * Created by PhpStorm.
 * User: Honoré
 * Date: 12/04/2018
 * Time: 15:14
 */

namespace OC\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\PlatformBundle\Entity\Category;


class LoadCategory implements FixtureInterface
{
//Dans l'argument de la méthode load, l'objet $manager est le gestionnaire
//d'entités

    public function load(ObjectManager $manager)
    {
    // Liste des noms de catégories à ajouter
        $names=array('Développement web',
            'Développement mobile',
            'Graphisme',
            'Intégration',
            'réseau');

        foreach($names as $name){
            //On crée la catégorie
            $category = new Category();
            $category->setName($name);

            //On la fait persister
            $manager->persist($category);
        }
        //On déclenche l'enregistrement de toutes les catégories
        $manager->flush();
    }
}