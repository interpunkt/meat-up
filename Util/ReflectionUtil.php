<?php

namespace DL\MeatUp\Util;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;

class ReflectionUtil
{
    /**
     * Doctrine annotations
     */
    const DOCTRINE_ID_ANNOTATION = 'Doctrine\ORM\Mapping\Id';
    const DOCTRINE_COLUMN_ANNOTATION = 'Doctrine\ORM\Mapping\Column';
    const DOCTRINE_MANY_TO_ONE_ANNOTATION = 'Doctrine\ORM\Mapping\ManyToOne';

    /**
     * meat-up annotations
     */
    const MANY_TO_ONE_ORDER_BY = 'DL\MeatUp\Mapping\ManyToOneOrderBy';

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
        return $this->hasAnnotation($property, self::DOCTRINE_ID_ANNOTATION);
    }

    public function getType($property)
    {
        if ($this->hasAnnotation($property, self::DOCTRINE_COLUMN_ANNOTATION))
        {
            return $this->getAnnotationAttribute(
                $property,
                self::DOCTRINE_COLUMN_ANNOTATION,
                'type'
            );
        }
        elseif ($this->hasAnnotation($property, self::DOCTRINE_MANY_TO_ONE_ANNOTATION))
        {
            return 'manyToOne';
        }

        return false;
    }

    public function getName($property)
    {
        return $property->getName();
    }

    public function getRequired($property)
    {
        $nullable = $this->getAnnotationAttribute(
            $property,
            self::DOCTRINE_COLUMN_ANNOTATION,
            'nullable'
        );

        return $nullable !== false ? 'false' : 'true';
    }

    private function getAnnotationAttribute($property, $annotationName, $attributeName)
    {
        $annotation = $this->annotationReader
            ->getPropertyAnnotation($property, $annotationName);

        if (is_null($annotation) || !array_key_exists($attributeName, $annotation))
        {
            return false;
        }

        return $annotation->$attributeName;
    }

    public function hasAnnotation($property, $annotationName)
    {
        $annotation = $this->annotationReader
            ->getPropertyAnnotation($property, $annotationName);

        return !empty($annotation);
    }

    public function getManyToOneTargetEntity($property)
    {
        return $this->getAnnotationAttribute($property,
            self::DOCTRINE_MANY_TO_ONE_ANNOTATION, 'targetEntity');
    }

    public function hasManyToOneOrderBy($property)
    {
        return $this->hasAnnotation($property, self::MANY_TO_ONE_ORDER_BY);
    }

    public function getManyToOneOrderByName($property)
    {
        return $this->getAnnotationAttribute($property,
            self::MANY_TO_ONE_ORDER_BY, 'propertyName');
    }

    public function getManyToOneOrderByDirection($property)
    {
        return $this->getAnnotationAttribute($property,
            self::MANY_TO_ONE_ORDER_BY, 'orderDirection');
    }
}