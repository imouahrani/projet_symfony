<?php
/**
 * Created by PhpStorm.
 * User: Honoré
 * Date: 04/04/2018
 * Time: 14:06
 */

namespace OC\PlatformBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class EndController extends Controller
{
    public function indexAction(){
        $content = $this->get('templating')->render('OCPlatformBundle:End:index.html.twig', array('nom'=>'honoré'));
        return new Response($content);
    }

    public function editImageAction($advertId){
        $em=$this->getDoctrine()->getManager();
        //On récupère l'annonce
        $advert=$em->getRepository('OCPlatformBundle:Advert')->find($advertId);
        //On modifie l'URL de l'image par exemple
        $advert->getImage()->setURL('test.png');

        //On n'a pas besoin de faire persister l'annonce ni l'image.
        //En effet, ces entités sont automatiquement prises en charge
        //car on les a récupérées depuis Doctrine lui-même.

        //On déclenche la modification
        $em->flush();
        return new Response('OK');
    }

}