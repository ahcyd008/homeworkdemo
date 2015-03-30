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
 * Description of SelectRoleType
 *
 * @author wing
 */
class SelectRoleType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('role', 'choice', 
                array(  
                        'choices'=>array('学生'=>'学生', '老师'=>'老师'),
                        'expanded' => true,
                        'multiple' => false
                    )
                )
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\SelectRole'
        ));
    }

    public function getName()
    {
        return 'select_role';
    }
}
