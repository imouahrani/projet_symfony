<?php
/**
 * Created by PhpStorm.
 * User: Honoré
 * Date: 10/04/2018
 * Time: 14:06
 */

//src /OC
namespace OC\PlatformBundle\Antispam;


class OCAntispam
{
    private $mailer;
    private $locale;
    private $minLength;

    /**
     * OCAntispam constructor.
     * @param $mailer
     * @param $locale
     * @param $minLength
     */
    public function __construct(\Swift_Mailer $mailer, $minLength)
    {
        $this->mailer = $mailer;
        $this->minLength = (int) $minLength;
    }


    /**
     * Vérifie si le texte est un spam ou non
     *
     * @param string $text
     * @return bool
     */
    public function isSpam($text)
    {
        return strlen($text) < $this->minLength;
    }

    public function setLocale($locale){
        $this->locale = $locale;
    }


}