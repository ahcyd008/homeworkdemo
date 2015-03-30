<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
/**
 * Description of HomeworkType
 *
 * @author wing
 */
class HomeworkType  extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text');
        $builder->add('content', 'textarea');
        
        $builder->add('file', 'vich_file', array(
               'required'      => false,
               'allow_delete'  => true, // not mandatory, default is true
               'download_link' => true, // not mandatory, default is true
           ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Homework'
        ));
    }

    public function getName()
    {
        return 'homework';
    }
}
