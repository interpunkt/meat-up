<?php

namespace DL\MeatUp\Util;

final class FormImportUtil
{
    private static $formImportList = array(
        "string" => "Symfony\\Component\\Form\\Extension\\Core\\Type\\TextType",
        "datetime" => "Symfony\\Component\\Form\\Extension\\Core\\Type\\DateTimeType"
    );

    public static function getImport($type) {
        if (key_exists($type, self::$formImportList)) {
            return self::$formImportList[$type];
        }

        throw new \RuntimeException('FormImportUtil: Unknown type: ' . $type);
    }
}