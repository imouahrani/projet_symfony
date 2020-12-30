<?php
/**
 * Created by PhpStorm.
 * User: Honoré
 * Date: 22/04/2018
 * Time: 20:45
 */

namespace OC\PlatformBundle\Beta;

use Symfony\Component\HttpFoundation\Response;


class BetaHTMLAdder
{
    //Méthode pour ajouter le "bêta" à une reponse
    public function addBeta(Response $response, $remainingDays)
    {
        $content = $response->getContent();

        //Code à ajouter
        //(Je mets ici du CSS en ligne, mais il faudrait utiliser un fichier CSS bien sur !)
        $html = '<div style="position: absolute; top: 0; background: orange; width: 100%; text-align: center; padding: 0.5em;">Beta-J' . (int)$remainingDays . ' !</div>';

        //Insertion du code dans la page, au début du <body>
        $content = str_replace('<body>', '<body>' . $html, $content);

        // Modification du contenu dans la réponse
        $response->setContent($content);
        return $response;
    }

}