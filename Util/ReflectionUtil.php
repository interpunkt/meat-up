<?php


namespace DL\MeatUpBundle\Util;


use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;

class ReflectionUtil
{
    const DOCTRINE_ID_ANNOTATION = 'Doctrine\ORM\Mapping\Id';
    const DOCTRINE_COLUMN_ANNOTATION = 'Doctrine\ORM\Mapping\Column';

    private $annotationReader;
    private $reflectedClass;

    public function __construct($className)
    {
        // TODO use cached reader
        $this->annotationReader = new AnnotationReader();
        $this->reflectedClass = new ReflectionClass($className);
    }

    public function getClassShortName()
    {
        return $this->reflectedClass->getShortName();
    }

    public function getProperties()
    {
        return $this->reflectedClass->getProperties();
    }

    public function getPropertyAnnotations($property)
    {
        return $this->annotationReader->getPropertyAnnotations($property);
    }

    public function isId($property)
    {

        $annotation = $this->annotationReader
            ->getPropertyAnnotation($property, self::DOCTRINE_ID_ANNOTATION);

        return !empty($annotation);
    }

    public function getTyp($property)
    {
        return $this->getColumnField($property, 'type');
    }

    public function getName($property)
    {

        return $property->getName();
    }

    public function getRequired($property)
    {
        $nullable = $this->getColumnField($property, 'nullable');

        return $nullable !== false ? 'false' : 'true';
    }

    private function getColumnField($property, $fieldName)
    {
        $annotation = $this->annotationReader
            ->getPropertyAnnotation($property, self::DOCTRINE_COLUMN_ANNOTATION);

        if (is_null($annotation) || !array_key_exists($fieldName, $annotation))
        {
            return false;
        }

        return $annotation->$fieldName;
    }
}