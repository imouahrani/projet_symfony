<?php
/**
 * Created by PhpStorm.
 * User: Honoré
 * Date: 22/04/2018
 * Time: 22:20
 */

namespace OC\PlatformBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;

class MessagePostEvent extends Event
{
    protected $message;
    protected $user;

    public function __construct($message, UserInterface $user)
    {
        $this->message = $message;
        $this->user = $user;
    }

    //L'écouteur doit avoir accès au message
    public function getMessage()
    {
        return $this->message;
    }

    // L'écouteur doit pouvoir modifier le message.
    public function setMessage($message)
    {
        return $this->message = $message;
    }

    // L'écouteur doit avoir accès à l'utilisateur
    public function getUser()
    {
        return $this->user;
    }

    //Pas de setUser, les écouteurs ne peuvent pas modifier l'auteur du message !

}