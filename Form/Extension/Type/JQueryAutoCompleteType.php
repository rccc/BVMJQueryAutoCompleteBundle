<?php
namespace BSky\Bundle\JQueryAutoCompleteBundle\Form\Extension\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Exception\FormException;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class JQueryAutoCompleteType extends TextType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        if (!isset($options['route'])){
             throw new FormException('The "route" parameter is mandatory');
        }
        
        $builder->setAttribute('route', $options['route']);
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $view->set('route', $form->getAttribute('route'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getParent(array $options)
    {
        return 'text';
    }
    
    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        return array('route' => $options['route']);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bsky_jqueryautocomplete';
    }
}