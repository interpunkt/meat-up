<?php

namespace DL\MeatUp\Util;

final class FormImportUtil
{
    private static $formImportList = array(
        "string" => "use Symfony\\Component\\Form\\Extension\\Core\\Type\\TextType;",
        "datetime" => "use Symfony\\Component\\Form\\Extension\\Core\\Type\\DateTimeType;",
        "date" => "use Symfony\\Component\\Form\\Extension\\Core\\Type\\DateType;",
        "text" => "use Symfony\\Component\\Form\\Extension\\Core\\Type\\TextareaType;",
        "integer" => "use Symfony\\Component\\Form\\Extension\\Core\\Type\\IntegerType;",
        "manyToOne" => "use Symfony\\Bridge\\Doctrine\\Form\\Type\\EntityType;" . PHP_EOL .
                "use Doctrine\\ORM\\EntityRepository;",
        "ckeditor" => "use Ivory\\CKEditorBundle\\Form\\Type\\CKEditorType;"
    );

    public static function getImport($type)
    {
        if ( ! key_exists($type, self::$formImportList))
        {
            throw new \RuntimeException('FormImportUtil: Unknown type: ' . $type);
        }

        return self::$formImportList[$type];
    }
}