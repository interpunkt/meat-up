<?php

namespace DL\MeatUp\Util;

final class FormImportUtil
{
    private static $formImportList = array(
        "string" => "Symfony\\Component\\Form\\Extension\\Core\\Type\\TextType",
        "datetime" => "Symfony\\Component\\Form\\Extension\\Core\\Type\\DateTimeType",
        "date" => "Symfony\\Component\\Form\\Extension\\Core\\Type\\DateType",
        "text" => "Symfony\\Component\\Form\\Extension\\Core\\Type\\TextareaType",
        "integer" => "Symfony\\Component\\Form\\Extension\\Core\\Type\\IntegerType",
        "manyToOne" => " Symfony\\Bridge\\Doctrine\\Form\\Type\\EntityType",
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