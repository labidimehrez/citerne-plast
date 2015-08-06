<?php

namespace MyApp\UtilisateurBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UtilisateurType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('login')
            ->add('password')
            ->add('email')
//            ->add('privilege')
//            ->add('datelog')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MyApp\UtilisateurBundle\Entity\Utilisateur'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'myapp_utilisateurbundle_utilisateur';
    }
}
