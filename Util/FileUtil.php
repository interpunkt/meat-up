<?php

namespace DL\MeatUp\Util;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

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
            try {
                mkdir($path, 0777, true);
            }
            catch (IOException $e) {
                return false;
            }

            return true;
        }
        return true;
    }
}