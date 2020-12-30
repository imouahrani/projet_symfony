<?php
/**
 * Created by PhpStorm.
 * User: Honoré
 * Date: 14/04/2018
 * Time: 20:47
 */

namespace OC\PlatformBundle\Email;

use OC\PlatformBundle\Entity\Application;


class ApplicationMailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function _construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendNewNotification(Application $application)
    {
        $message = new \Swift_Message('Nouvelle candidature', 'Vous avez reçu une nouvelle candidature.');

        $message->addTo($application->getAdvert()->getAuthor())
            //Ici bien sur il faudrait un attribut "email", j'utilise "author" à la place
        ->addFrom('honore.rasamoelina@gmail.com');
        $this->mailer->send($message);
    }
}