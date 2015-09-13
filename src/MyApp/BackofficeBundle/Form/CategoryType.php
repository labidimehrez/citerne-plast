<?php

namespace MyApp\BackofficeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('nom', 'text', array('required' => TRUE))
                ->add('position', 'integer', array('required' => TRUE))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function __construct(array $options = array()) {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'MyApp\BackofficeBundle\Entity\Category'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'myapp_backofficebundle_category';
    }

}
