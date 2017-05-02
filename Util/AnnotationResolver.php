<?php

namespace DL\MeatUp\Util;

class AnnotationResolver
{
    const ANNOTATION = 0;
    const ATTRIBUTE = 1;

    /**
     * attribute or no attribute, that is here the question
     *
     * Example:
     * @ORM\Column(type="string", length=255)
     *
     * Column is an annotation and doesn't need the attribute value
     * type & length are attributes of an annotation, they would need
     * the attribute value type respectively length
     */
    private static $annotationsList = array(
        // MeatUp annotations
        'ManyToOneOrderBy' => ['name' => 'DL\MeatUp\Mapping\ManyToOneOrderBy'],
        'ManyToOneOrderByName' => ['name' => 'DL\MeatUp\Mapping\ManyToOneOrderBy', 'attribute' => 'propertyName'],
        'ManyToOneOrderByDirection' => ['name' => 'DL\MeatUp\Mapping\ManyToOneOrderBy', 'attribute' => 'orderDirection'],
        'OnIndexPage' => ['name' => 'DL\MeatUp\Mapping\OnIndexPage'],
        'OnIndexPageLabel' => ['name' => 'DL\MeatUp\Mapping\OnIndexPage', 'attribute' => 'label'],
        'OnIndexPageFilter' => ['name' => 'DL\MeatUp\Mapping\OnIndexPage', 'attribute' => 'filter'],
        'OnIndexPageFilterParameters' => ['name' => 'DL\MeatUp\Mapping\OnIndexPage', 'attribute' => 'filterParameters'],

        // Doctrine annotations
        'Id' => ['name' => 'Doctrine\ORM\Mapping\Id'],
        'ManyToOneTargetEntity' => ['name' => 'Doctrine\ORM\Mapping\ManyToOne', 'attribute' => 'targetEntity']
    );

    public static function resolve($name)
    {
        if (!key_exists($name, self::$annotationsList))
        {
            return false;
        }

        return self::$annotationsList[$name];
    }
}