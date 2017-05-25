<?php

namespace Ip\MeatUp\Util;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;

class AnnotationUtil
{
    private $annotationReader;
    private $reflectionClass;

    public function __construct(AnnotationReader $annotationReader, ReflectionClass $reflectionClass)
    {
        $this->annotationReader = $annotationReader;
        $this->reflectionClass = $reflectionClass;
    }

    public function getClassShortName()
    {
        return $this->reflectionClass->getShortName();
    }

    public function getProperties()
    {
        return $this->reflectionClass->getProperties();
    }

    public function getPropertyAnnotations($property)
    {
        return $this->annotationReader->getPropertyAnnotations($property);
    }

    public function getType($property)
    {
        if ($this->has('Hidden', $property)) {
            return 'hidden';
        } elseif ($this->has('VichUploadable', $property)) {
            return 'vichUploadable';
        } elseif ($this->has('Column', $property)) {
            $columnType = $this->get('Column', 'type', $property);

            if ($columnType == 'decimal' || $columnType == 'float') {
                return 'number';
            }

            if ($columnType == 'text' && $this->has('CKEditor', $property)) {
                return 'ckeditor';
            }

            return $columnType;
        } elseif ($this->has('ManyToOne', $property)) {
            return 'manyToOne';
        }

        return false;
    }

    /**
     * @param \ReflectionProperty $property
     * @return mixed
     */
    public function getName($property)
    {
        return $property->getName();
    }

    public function getRequired($property)
    {
        $nullable = $this->get('Column', 'nullable', $property);

        return $nullable !== false;
    }

    public function get($key, $attribute, $property)
    {
        $resolvedAnnotation = AnnotationResolver::resolve($key);

        if ($resolvedAnnotation === false) {
            throw new \BadMethodCallException(
                'Method has' . $key . '. is not implemented in AnnotationResolver'
            );
        }

        $annotation = $this->annotationReader
            ->getPropertyAnnotation($property, $resolvedAnnotation);

        if (is_null($annotation)) {
            return false;
        }

        $attributeValue = $this->getAnnotationValue($annotation, $attribute);

        if (is_null($attributeValue)) {
            return false;
        }

        return $attributeValue;
    }

    public function has($key, $property)
    {
        $resolvedAnnotation = AnnotationResolver::resolve($key);

        if ($resolvedAnnotation === false) {
            throw new \BadMethodCallException(
                'Method has' . $key . '. is not implemented in AnnotationResolver'
            );
        }

        $annotation = $this->annotationReader
            ->getPropertyAnnotation($property, $resolvedAnnotation);

        return !empty($annotation);
    }

    private function getAnnotationValue($annotation, $attribute)
    {
        $reflection = new ReflectionClass($annotation);
        $property = $reflection->getProperty($attribute);
        $property->setAccessible(true);
        return $property->getValue($annotation);
    }
}
