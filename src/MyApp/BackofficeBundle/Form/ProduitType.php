<?php

namespace MyApp\BackofficeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('image')
                ->add('qteStock')
                ->add('qteCommand')
                ->add('nouveauprix')
                ->add('ancienprix')
                ->add('nomproduit', 'text', array('required' => TRUE))
                ->add('detailsproduit')


                # ->add('state')
                ->add('state', 'entity', array('class' => 'MyApp\BackofficeBundle\Entity\State',
                    'choice_label' => 'nomState',
                    'expanded' => false,
                    'multiple' => false,
                    'required' => false))



//            ->add('category')
                ->add('category', 'entity', array('class' => 'MyApp\BackofficeBundle\Entity\Category',
                    'choice_label' => 'nom', // avoid deprecated 'property'=>'' since sf2.7
                    'expanded' => false,
                    'multiple' => false,
                    'required' => false))
                
//            ->add('departement')
                   ->add('departement', 'entity', array('class' => 'MyApp\BackofficeBundle\Entity\Departement',
                    'choice_label' => 'nom',
                    'expanded' => false,
                    'multiple' => false,
                    'required' => false))
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
            'data_class' => 'MyApp\BackofficeBundle\Entity\Produit'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'myapp_backofficebundle_produit';
    }

}
