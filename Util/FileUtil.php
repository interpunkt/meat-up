<?php

namespace Ip\MeatUp\Util;

class FileUtil
{
    private $lockFile;

    public function __construct(LockFileUtil $lockFile)
    {
        $this->lockFile = $lockFile;
    }

    public function writeToFile($content, $filePath)
    {
        if (!$this->lockFile->isSafeToWrite($filePath)) {
            $this->output->writeln(
                'Can\'t write to the file '.$filePath.': '.$this->lockFile->getErrorMsg()
            );
            return false;
        }

        $fp = fopen($filePath, 'w');

        if ($fp === false) {
            return false;
        }

        $isWriteSuccessful = fwrite($fp, $content) !== false;

        if ($isWriteSuccessful) {
            $this->lockFile->addToLockFile($filePath);
        }

        return $isWriteSuccessful;
    }

    public static function createDirectory($path)
    {
        if (!file_exists($path)) {
            return mkdir($path, 0777, true);
        }

        return true;
    }
}