<?php

namespace Ip\MeatUp\Util;

class AnnotationResolver
{
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
        // MeatUp
        'ManyToOneOrderBy' => ['name' => 'Ip\MeatUp\Mapping\ManyToOneOrderBy'],
        'ManyToOneOrderByNames' => ['name' => 'Ip\MeatUp\Mapping\ManyToOneOrderBy', 'attribute' => 'propertyNames'],
        'ManyToOneOrderByDirections' => ['name' => 'Ip\MeatUp\Mapping\ManyToOneOrderBy', 'attribute' => 'orderDirections'],
        'OnIndexPage' => ['name' => 'Ip\MeatUp\Mapping\OnIndexPage'],
        'OnIndexPageLabel' => ['name' => 'Ip\MeatUp\Mapping\OnIndexPage', 'attribute' => 'label'],
        'OnIndexPageFilter' => ['name' => 'Ip\MeatUp\Mapping\OnIndexPage', 'attribute' => 'filter'],
        'OnIndexPageFilterParameters' => ['name' => 'Ip\MeatUp\Mapping\OnIndexPage', 'attribute' => 'filterParameters'],
        'ManyToOneChoice' => ['name' => 'Ip\MeatUp\Mapping\ManyToOneChoice'],
        'ManyToOneChoiceLabels' => ['name' => 'Ip\MeatUp\Mapping\ManyToOneChoice', 'attribute' => 'labels'],
        'Ignore' => ['name' => 'Ip\MeatUp\Mapping\Ignore'],
        'Hidden' => ['name' => 'Ip\MeatUp\Mapping\Hidden'],
        'CKEditor' => ['name' => 'Ip\MeatUp\Mapping\CKEditor'],
        'CKEditorConfig' => ['name' => 'Ip\MeatUp\Mapping\CKEditor', 'attribute' => 'config'],

        // Doctrine
        'Column' => ['name' => 'Doctrine\ORM\Mapping\Column'],
        'ColumnType' => ['name' => 'Doctrine\ORM\Mapping\Column', 'attribute' => 'type'],
        'ColumnNullable' => ['name' => 'Doctrine\ORM\Mapping\Column', 'attribute' => 'nullable'],
        'ColumnScale' => ['name' => 'Doctrine\ORM\Mapping\Column', 'attribute' => 'scale'],
        'Id' => ['name' => 'Doctrine\ORM\Mapping\Id'],
        'ManyToOne' => ['name' => 'Doctrine\ORM\Mapping\ManyToOne'],
        'ManyToOneTargetEntity' => ['name' => 'Doctrine\ORM\Mapping\ManyToOne', 'attribute' => 'targetEntity'],

        // Vich uploader
        'VichUploadable' => ['name' => 'Vich\UploaderBundle\Mapping\Annotation\UploadableField'],
        'VichUploadableFileNameProperty' => ['name' => 'Vich\UploaderBundle\Mapping\Annotation\UploadableField', 'attribute' => 'fileNameProperty'],
    );

    public static function resolve($name)
    {
        if (!key_exists($name, self::$annotationsList)) {
            return false;
        }

        return self::$annotationsList[$name];
    }
}