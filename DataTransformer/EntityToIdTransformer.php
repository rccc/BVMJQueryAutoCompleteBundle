<?php

namespace Rccc\JQueryAutoCompleteBundle\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\FormException;
use Symfony\Component\Form\Util\PropertyPath;

class EntityToIdTransformer implements DataTransformerInterface 
{
    protected $em;
    protected $class;
    protected $property;
    protected $propertyDisplay;
    
    public function __construct(EntityManager $em, $class, $property, $propertyDisplay)
    {
        $this->em = $em;
        $this->class = $class;
        $this->property = $property;
        $this->propertyDisplay = $propertyDisplay;
    }
    
    /**
     * {@inheritdoc}
     */
    public function transform($data)
    {
        if (null === $data) {
            return null;
        }
        
        $propertyPath = new PropertyPath($this->property);
        
        $return = array('id' => $propertyPath->getValue($data));
        
        if ($this->propertyDisplay == null) {
            $return['value'] = (string) $data;
        } else {
            $propertyDisplay = new PropertyPath($this->propertyDisplay);
            $return['value'] = $propertyDisplay->getValue($data);
        }
        
        return $return;
    }
    
    /**
     * {@inheritdoc}
     */
    public function reverseTransform($data)
    {
        if (!$data) {
            return null;
        }

        $em = $this->em;
        $class = $this->class;
        $repository = $em->getRepository($class);

        $result = $repository->findOneBy(array($this->property => $data));

        if (!$result) {
            throw new TransformationFailedException('Can not find entity');
        }

        return $result;
    }
}