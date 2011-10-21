<?php
namespace BSky\Bundle\JQueryAutoCompleteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Exception\FormException;

class JQueryAutoCompleteType extends AbstractType{
    
    
    //route 
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