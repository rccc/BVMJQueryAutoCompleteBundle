<?php

namespace Rccc\JQueryAutoCompleteBundle\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\FormException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyPath;

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
        
        $propertyAccessor = PropertyAccess::getPropertyAccessor();
        $propertyPath = new PropertyPath($this->property);
        
        $value = $propertyAccessor->getValue($data, $propertyPath);
        
        $return = array('id' => $value);
        
        if ($this->propertyDisplay == null) {
            $return['value'] = (string) $data;
        } else {
            $propertyDisplay = new PropertyPath($this->propertyDisplay);
            $value = $propertyAccessor->getValue($data, $propertyDisplay);
            $return['value'] = $value;
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