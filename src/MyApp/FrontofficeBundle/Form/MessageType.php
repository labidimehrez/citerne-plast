<?php

namespace MyApp\FrontofficeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MessageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('required' => TRUE))
            ->add('email', 'email', array(
                'required' => TRUE,
                'attr' => array(
                    'placeholder' => 'So I can get back to you.'
                )
            ))
            ->add('subject')
            ->add('message')
            #   ->add('lu')
            #   ->add('dateCreation')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MyApp\FrontofficeBundle\Entity\Message'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'myapp_frontofficebundle_message';
    }
}
