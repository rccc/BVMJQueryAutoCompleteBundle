<?php
namespace BSky\Bundle\JQueryAutoCompleteBundle\Form\Extension\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Exception\FormException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityManager;

use BSky\Bundle\JQueryAutoCompleteBundle\DataTransformer\EntityToIdTransformer;

class JQueryAutoCompleteType extends TextType
{
    protected $em;
    
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        if (!isset($options['route'])){
             throw new FormException('The "route" parameter is mandatory');
        }
        
        if (!isset($options['class'])){
             throw new FormException('The "class" parameter is mandatory');
        }
        
        $property_display = null;
        
        if (isset($options['property_display'])){
             $property_display = $options['property_display'];
        }
        
        $builder->setAttribute('route', $options['route']);
        
        $builder->prependClientTransformer(new EntityToIdTransformer(
            $this->em,
            $options['class'],
            $options['property'],
            $property_display
        ));
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
        if(!isset($options['property'])){
            $options['property'] = 'id';
        }
        
        if(!isset($options['route'])){
            throw new FormException('The "route" parameter is mandatory');
        }
        
        if(!isset($options['class'])){
            throw new FormException('The "class" parameter is mandatory');
        }
        
        return $options;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bsky_jqueryautocomplete';
    }
}