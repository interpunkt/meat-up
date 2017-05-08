<?php

namespace Ip\MeatUp\Util;

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
        'ManyToOneOrderBy' => ['name' => 'Ip\MeatUp\Mapping\ManyToOneOrderBy'],
        'ManyToOneOrderByNames' => ['name' => 'Ip\MeatUp\Mapping\ManyToOneOrderBy', 'attribute' => 'propertyNames'],
        'ManyToOneOrderByDirections' => ['name' => 'Ip\MeatUp\Mapping\ManyToOneOrderBy', 'attribute' => 'orderDirections'],
        'OnIndexPage' => ['name' => 'Ip\MeatUp\Mapping\OnIndexPage'],
        'OnIndexPageLabel' => ['name' => 'Ip\MeatUp\Mapping\OnIndexPage', 'attribute' => 'label'],
        'OnIndexPageFilter' => ['name' => 'Ip\MeatUp\Mapping\OnIndexPage', 'attribute' => 'filter'],
        'OnIndexPageFilterParameters' => ['name' => 'Ip\MeatUp\Mapping\OnIndexPage', 'attribute' => 'filterParameters'],
        'ManyToOneChoice' => ['name' => 'Ip\MeatUp\Mapping\ManyToOneChoice'],
        'ManyToOneChoiceLabels' => ['name' => 'Ip\MeatUp\Mapping\ManyToOneChoice', 'attribute' => 'labels'],

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