<?php

namespace Ip\MeatUp\Util;

class AnnotationResolver
{
    private static $annotationsList = array(
        // MeatUp
        'ManyToOneOrderBy' => 'Ip\MeatUp\Mapping\ManyToOneOrderBy',
        'OnIndexPage' => 'Ip\MeatUp\Mapping\OnIndexPage',
        'ManyToOneChoice' => 'Ip\MeatUp\Mapping\ManyToOneChoice',
        'Ignore' => 'Ip\MeatUp\Mapping\Ignore',
        'Hidden' => 'Ip\MeatUp\Mapping\Hidden',
        'CKEditor' => 'Ip\MeatUp\Mapping\CKEditor',

        // Doctrine
        'Column' => 'Doctrine\ORM\Mapping\Column',
        'Id' => 'Doctrine\ORM\Mapping\Id',
        'ManyToOne' => 'Doctrine\ORM\Mapping\ManyToOne',

        // Vich uploader
        'VichUploadable' => 'Vich\UploaderBundle\Mapping\Annotation\UploadableField',

        // StofDoctrineExtensionsBundle
        'SortablePosition' => 'Gedmo\Mapping\Annotation\SortablePosition',
    );

    public static function resolve($name)
    {
        if (!key_exists($name, self::$annotationsList)) {
            return false;
        }

        return self::$annotationsList[$name];
    }
}
