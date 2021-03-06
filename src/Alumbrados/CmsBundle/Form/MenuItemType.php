<?php

namespace Alumbrados\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MenuItemType extends AbstractType
{
    public function __construct() {
        
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parentDocument', 'phpcr_document', array(
                'property' => 'label',
                'class' => 'Alumbrados\CmsBundle\Document\Menu',
            ))                
            ->add('name', 'text', array(
                'label' => 'name'
            ))
            ->add('label', 'text', array(
                'label' => 'label'
            ))
            ->add('content', 'phpcr_document', array(
                'property' => 'name',
                'class' => 'Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route',
                'required' => true,
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Alumbrados\CmsBundle\Document\MenuNode'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'alumbrados_cmsbundle_menu_item';
    }
}
