<?php

namespace Alumbrados\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'label' => 'title'
            ))
            ->add('date', 'date', array(
                'label' => 'date'
            ))                
            ->add('content', 'ckeditor', array(
                'label' => 'content'
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Alumbrados\CmsBundle\Document\Post'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'alumbrados_cmsbundle_post';
    }
}
