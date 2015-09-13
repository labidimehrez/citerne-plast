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
                    'property' => 'nomState',
                    'expanded' => false,
                    'multiple' => false,
                    'required' => true))



//            ->add('category')
                ->add('category', 'entity', array('class' => 'MyApp\BackofficeBundle\Entity\Category',
                    'property' => 'nom',
                    'expanded' => false,
                    'multiple' => false,
                    'required' => true))
                
//            ->add('departement')
                   ->add('departement', 'entity', array('class' => 'MyApp\BackofficeBundle\Entity\Departement',
                    'property' => 'nom',
                    'expanded' => false,
                    'multiple' => false,
                    'required' => true))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
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
