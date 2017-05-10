<?php

namespace Ip\MeatUp\Util;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;

class AnnotationUtil
{
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

    public function getType($property)
    {
        if ($this->hasHidden($property)) {
            return 'hidden';
        }
        elseif ($this->hasColumn($property)) {
            $columnType = $this->getColumnType($property);

            if ($columnType == 'decimal' || $columnType == 'float') {
                return 'number';
            }

            if ($columnType == 'text' && $this->hasCKEditor($property)) {
                return 'ckeditor';
            }

            return $columnType;
        }
        elseif ($this->hasManyToOne($property)) {
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
        $nullable = $this->getColumnNullable($property);

        $type = $this->getType($property);

        /**
         * boolean is never mandatory, otherwise it couldn't be left unchecked
         */
        if ($type == 'boolean') {
            return 'false';
        }

        return $nullable !== false ? 'false' : 'true';
    }

    private function getAnnotationAttribute($property, $annotationName, $attributeName)
    {
        $annotation = $this->annotationReader
            ->getPropertyAnnotation($property, $annotationName);

        if (is_null($annotation) || !array_key_exists($attributeName, $annotation)) {
            return false;
        }

        return $annotation->$attributeName;
    }

    private function hasAnnotation($property, $annotationName)
    {
        $annotation = $this->annotationReader
            ->getPropertyAnnotation($property, $annotationName);

        return !empty($annotation);
    }

    /**
     * Is called when a function is not defined. In this case it is used to resolve get and has
     * function calls
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed The returned value from the resolved method.
     *
     * @throws \BadMethodCallException If the method called is invalid
     */
    public function __call($method, $arguments)
    {
        if (count($arguments) != 1) {
            throw new \BadMethodCallException(
                'Method has has to be called with exactly one parameter'
            );
        }

        $property = $arguments[0];

        if (strpos($method, 'get') === 0) {
            return $this->resolveGetCall(substr($method, 3), $property);
        }

        if (strpos($method, 'has') === 0) {
            return $this->resolveHasCall(substr($method, 3), $property);
        }

        throw new \BadMethodCallException(
            "Undefined method '$method'. The method name must start with either get or has!"
        );
    }

    /**
     * Resolves a magic method call to check if an annotation exists
     *
     * @param string $key       The name of the corresponding annotation
     * @param string $property  The arguments to pass at method call
     *
     * @throws \BadMethodCallException If the method called is invalid or the requested annotation does not exist
     *
     * @return boolean
     */
    private function resolveHasCall($key, $property)
    {
        $resolvedAnnotation = AnnotationResolver::resolve($key);

        if ($resolvedAnnotation === false) {
            throw new \BadMethodCallException(
                'Method has' . $key . '. is not implemented in AnnotationResolver'
            );
        }

        return $this->hasAnnotation($property, $resolvedAnnotation['name']);
    }

    /**
     * Resolves a magic method call to return an attribute of an annotation
     *
     * @param string $key       The name of the corresponding attribute
     * @param string $property  The arguments to pass at method call
     *
     * @throws \BadMethodCallException If the method called is invalid or the requested attribute does not exist
     *
     * @return mixed
     */
    private function resolveGetCall($key, $property)
    {
        $resolvedAnnotation = AnnotationResolver::resolve($key);

        if ($resolvedAnnotation === false) {
            throw new \BadMethodCallException(
                'Method has' . $key . '. is not implemented in AnnotationResolver'
            );
        }

        return $this->getAnnotationAttribute(
            $property,
            $resolvedAnnotation['name'],
            $resolvedAnnotation['attribute']
        );
    }
}