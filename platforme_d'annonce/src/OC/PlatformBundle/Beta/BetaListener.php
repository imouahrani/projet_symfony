<?php
/**
 * Created by PhpStorm.
 * User: Honoré
 * Date: 22/04/2018
 * Time: 21:03
 */

namespace OC\PlatformBundle\Beta;

use Symfony\Component\HttpFoundation\Response;


class BetaListener
{
    //Notre processeur
    protected $betaHTML;

    // La date de fin de la version bêta :
    // - Avant cette date, on n'affichera un compte à rebours (J-3 par exemple).
    // - Après cette date, on n'affichera plus le bêta

    protected $endDate;

    public function __construct(BetaHTMLAdder $betaHTML, $endDate)
    {
        $this->betaHTML=$betaHTML;
        $this->endDate=new \DateTime($endDate);
    }

    public function processBeta(){
        $remainingDays = $this->endDate->diff(new \DateTime())->days;

        if ($remainingDays<=0){
            // Si la date est dépassée, on ne fait rien
            return;
        }

        //Ici, on appellera la méthode
        //$this->betaHTML->addBeta();
    }

}