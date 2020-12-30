<?php
/**
 * Created by PhpStorm.
 * User: Honoré
 * Date: 22/04/2018
 * Time: 18:33
 */

namespace OC\PlatformBundle\Twig;
use OC\PlatformBundle\Antispam\OCAntispam;

class AntispamExtension extends \Twig_Extension
{
    /**
     * @var ocAntispam
     */
    private $ocAntispam;

    public function __construct(OCAntispam $ocAntispam)
    {
        $this->ocAntispam = $ocAntispam;
    }

    public function checkIfArgumentIsSpam($text)
    {
        return $this->ocAntispam->isSpam($text);
    }

    //Twig va executer cette méthode pour savoir quelle(s) fonction(s) ajoute notre service
    public function getFunctions(){
        return array( new \Twig_SimpleFunction('checkIfSpam',array($this,'checkIfArgumentIsSpam')),);
    }

    //La méthode getName() identifie votre extension Twig, elle est obligatoire
    public function getName(){
        return 'OCAntispam';
    }


}