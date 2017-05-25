<?php

namespace Ip\MeatUp\Util;

class FormImportUtil
{
    private static $formImportList = array(
        'string' => 'Symfony\Component\Form\Extension\Core\Type\TextType',
        'datetime' => 'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
        'date' => 'Symfony\Component\Form\Extension\Core\Type\DateType',
        'text' => 'Symfony\Component\Form\Extension\Core\Type\TextareaType',
        'integer' => 'Symfony\Component\Form\Extension\Core\Type\IntegerType',
        'hidden' => 'Symfony\Component\Form\Extension\Core\Type\HiddenType',
        'number' => 'Symfony\Component\Form\Extension\Core\Type\NumberType',
        'ckeditor' => 'Ivory\CKEditorBundle\Form\Type\CKEditorType',
        'boolean' => 'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        'vichUploadable' => 'Vich\UploaderBundle\Form\Type\VichFileType',
        'manyToOne' => 'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        'manyToOneOrderBy' => 'Doctrine\ORM\EntityRepository',
    );

    public function getImport($type)
    {
        if (!key_exists($type, self::$formImportList)) {
            throw new \RuntimeException('FormImportUtil: Unknown type: ' . $type);
        }

        return self::$formImportList[$type];
    }
}
