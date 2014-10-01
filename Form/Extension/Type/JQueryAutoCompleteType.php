<?php
namespace Rccc\JQueryAutoCompleteBundle\Form\Extension\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Exception\FormException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Rccc\JQueryAutoCompleteBundle\DataTransformer\EntityToIdTransformer;

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
    public function buildForm(FormBuilderInterface $builder, array $options)
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

        $builder->addViewTransformer(new EntityToIdTransformer(
            $this->em,
            $options['class'],
            $options['property'],
            $property_display
        ), true);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['route'] = $form->getConfig()->getAttribute('route'); 
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'route' => '',
            'property' => 'id',
            'class' => '',
            'property_display' => null
        ));

        //return $options;
    }
    
    public function setSecurityContext($security_context)
    {
        $this->security_context  = $security_context;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'rccc_jqueryautocomplete';
    }
}