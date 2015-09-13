<?php

namespace MyApp\BackofficeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class registrerType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', 'text', array(
                'required' => TRUE,
                'attr' => array(
                    'placeholder' => 'What\'s your name?',
                    'pattern' => '.{5,}' //minlength
                )
            ))
            ->add('email', 'email', array(
                'required' => TRUE,
                'attr' => array(
                    'placeholder' => 'So I can get back to you.'
                )
            ))
            ->add('password', 'repeated', array(
                    'required' => TRUE,
                    'type' => 'password',
                    'invalid_message' => 'Les mots de passe doivent correspondre',
                    'options' => array('required' => true),
                    'first_options' => array('label' => 'Mot de passe'),
                    'second_options' => array('label' => 'Mot de passe (validation)'),)
            );
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
