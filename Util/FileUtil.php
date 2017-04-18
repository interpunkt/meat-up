<?php


namespace DL\MeatUp\Util;


class FileUtil
{
    public static function writeToFile($content, $filePath)
    {
        $fp = fopen($filePath, 'w');

        if ($fp === false) {
            return false;
        }

        return fwrite($fp, $content) !== false;
    }

    public static function createDirectory($path)
    {
        if (!file_exists($path))
        {
            return mkdir($path, 0600, true);
        }
        return true;
    }
}