<?php
/**
 * Created by PhpStorm.
 * User: Honoré
 * Date: 22/04/2018
 * Time: 19:00
 */

namespace OC\PlatformBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CkeditorType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'attr'=>array('class'=>'ckeditor')
        ));
    }

    public function getParent(){
        return TextareaType::class;
    }

}