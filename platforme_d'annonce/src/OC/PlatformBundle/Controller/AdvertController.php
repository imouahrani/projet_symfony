<?php
/**
 * Created by PhpStorm.
 * User: Honoré
 * Date: 04/04/2018
 * Time: 13:34
 */

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Form\AdvertType;
use OC\PlatformBundle\Entity\AdvertSkill;
use OC\PlatformBundle\Entity\Image;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Repository\AdvertRepository;
use OC\PlatformBundle\Form\AdvertEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class AdvertController extends Controller
{
    public function indexAction($page)
    {
        if ($page < 1) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        //Ici je fixe à 3 le nombre d'annonces par page.
        //Bien sur, il faudrait utiliser un paramètre et y accéder via $this->container->getParameter('nb_per_page').
        $nbPerPage = 3;

        //Pour récupérer la liste de toutes les annonces : on utilise findAll()
        $listAdverts = $this->getDoctrine()
            ->getManager()
            ->getRepository('OCPlatformBundle:Advert')
            ->findAll();

        //On calcule le nombre total de pages grâce au count($listAdverts) qui
        //retourne le nombre total d'annonces
        $nbPages = ceil(count($listAdverts) / $nbPerPage);

        //Si la page n'existe pas, on retourne une 404
        if ($page > $nbPages) {
            throw $this->createNotFoundException("La page " . $page . "n'existe pas.");
        }


        //On donne toutes les informations nécessaires à la vue
        return $this->render('OCPlatformBundle:Advert:index.html.twig', array('listAdverts' => $listAdverts, 'nbPages' => $nbPages, 'page' => $page,));
    }


    public function viewAction($id)
    {
        //On récupère le repository
        $em = $this->getDoctrine()->getManager();

        //On récupère l'annonce $id
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        //$advert est donc une instance de OC\PlatformBundle\Entity\Advert
        //ou null si l'id $id n'existe pas, d'où ce if.
        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }

        //On récupère la liste des candidatures à cette annonce.
        $listApplications = $em->getRepository('OCPlatformBundle:Application')->findBy(array('advert' => $advert));

        //On récupère la liste des AdvertSkill
        $listAdvertSkills = $em->getRepository('OCPlatformBundle:AdvertSkill')->findBy(array('advert' => $advert));
        // Ici, on récupérera l'annonce correspondant à l'id $id.
        return $this->render('OCPlatformBundle:Advert:view.html.twig', array('advert' => $advert, 'listApplications' => $listApplications, 'listAdvertSkills' => $listAdvertSkills));

    }


    public function addAction(Request $request)
    {
        // On vérifie que l'utilisateur dispose bien du rôle ROLE_AUTEUR

        //if(!$this->get('security.authorization_checker')->isGranted('ROLE_AUTEUR')){
            // Sinon on déclenche une exception "Accès interdit"
            //throw new AccessDeniedException('Accès limité aux auteurs.');
        //}


        //Si l'utilsateur a les droits suffisants, il peut ajouter une annonce
        //On ne sait toujours pas gérer le formulaire, patience cela vient dans
        //la prochaine partie
        $advert = new Advert();

        // Ici, on préremplit avec la date d'aujourd'hui, par exemple.
        // Cette date sera donc préafficher dans le formulaire, ce qui facilite le travail de l'utilisateur
        //On crée le FormBuilder grâce au service form factory.
        $form = $this->get('form.factory')->create(AdvertType::class, $advert);

        //Pour l'instant, pas de candidatures, catégories, etc., on les gérera
        //plus tard.
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            //Déplacement l'image la où on veut la stocker.
            //$advert->getImage()->upload();
            //On enregistre notre objet $advert dans la base de données, par exemple
            //On récupère le gestionnaire d'entités
            $em = $this->getDoctrine()->getManager();
            $em->persist($advert);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

            // On redirige vers la page de visualisation de l'annonce
            // Nouvellement créee
            return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
        }
        //A ce stade, le formulaire n'est pas valide car :
        // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
        // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau

        return $this->render('OCPlatformBundle:Advert:add.html.twig', array('form' => $form->createView(),));
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        //On récupère l'annonce $id
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id" . $id . " n'existe pas.");
        }

        $form = $this->get('form.factory')->create(AdvertEditType::class, $advert);
        // Ici, on récupère l'annonce correspondant à $id.
        // Même mécanisme que pour l'ajout
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');
            return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
        }
        return $this->render('OCPlatformBundle:Advert:edit.html.twig', array('advert' => $advert, 'form' => $form->createView(),));
    }

    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        //On récupère l'annonce $id
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id" . $id . " n'existe pas. ");
        }
        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->remove($advert);
            $em->flush();
            $request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimée");
            return $this->redirectToRoute('oc_platform_home');
        }


        return $this->render('OCPlatformBundle:Advert:delete.html.twig', array('advert' => $advert, 'form' => $form->createView(), ));
    }

    public function menuAction($limit)
    {
        $em = $this->getDoctrine()->getManager();
        $listAdverts = $em->getRepository('OCPlatformBundle:Advert')->findBy(array(), //Pas de critère
            array('date' => 'desc'), // On trie par date décroissante
            $limit, //On sélectionne $limit annonces
            0 //A partir du premier
        );
        return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
            //Tout l'intérêt est ici : le contrôleur passe
            //les variables nécessaires au template !
            'listAdverts' => $listAdverts
        ));
    }

    // Cette méthode récupère tous les paramètres en arguments de la méthode.
    public function viewSlugAction($slug, $year, $format)
    {
        return new Response("On pourrait afficher l'annonce correspondant au slug '" . $slug . "', créee en " . $year . " et au format " . $format . ".");
    }

    public function listCategory()
    {
        $listAdverts = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OCPlatformBundle:Advert')
            ->getAdvertWithCategories('Développement web');

        foreach ($listAdverts as $advert) {
            //Ne déclenche pas de requête : les catégories sont déjà chargées :
            //Vous pourriez faire une boucle dessus pour les afficher toutes.
            $advert->getCategories();
        }

    }

    public function listAdvert()
    {
        $listAdverts = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OCPlatformBundle:Application')
            ->getApplicationsWithAdvert('Honoré');

        foreach ($listAdverts as $advert) {
            //Ne déclenche pas de requête : les catégories sont déjà chargées :
            //Vous pourriez faire une boucle dessus pour les afficher toutes.
            $advert->getAdverts();
        }

    }

    public function testAction()
    {
        $advert = new Advert();
        $advert->setDate(new \DateTime());// Champ date ok
        $advert->setTitle('abc');// Champ title incorrect : moins de 10 caractères
        // $advert->setContent('blabla'); // Champ contient incorrect : on ne le définit pas
        $advert->setAuthor('A');//Champ author incorrect : moins de 2 caractères

        //On récupère le service validator
        $validator=$this->get('validator');

        //On déclenche la validation sur notre objet
        $listErrors = $validator->validate($advert);

        //Si $listErrors n'est pas vide, on affiche les erreurs
        if(count($listErrors)>0){
            // $listErrors est un objet , sa méthode __toString permet de lister joliment les erreurs
            return new Response((string) $listErrors);
        } else {
            return new Response("L'annonce est valide !");
        }

        //Affiche "Slug généré : recherche-developpeur".
    }

    public function translationAction($name){
        return $this->render('OCPlatformBundle:Advert:translation.html.twig', array('name'=>$name));
    }


}