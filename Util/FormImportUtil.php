<?php


namespace DL\MeatUpBundle\Util;


final class FormImportUtil
{
    private static $formImportList = array(
        "string" => "Symfony\\Component\\Form\\Extension\\Core\\Type\\TextType",
        "datetime" => "Symfony\\Component\\Form\\Extension\\Core\\Type\\DateTimeType"
    );

    public static function getImport($type) {
        return self::$formImportList[$type];
    }
}