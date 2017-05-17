<?php

namespace Ip\MeatUp\Util;

use Symfony\Component\Console\Output\OutputInterface;

class FileUtil
{
    private $lockFile;
    private $output;

    /**
     * @param LockFileUtil $lockFile
     * @param OutputInterface $output
     */
    public function __construct(LockFileUtil $lockFile, OutputInterface $output)
    {
        $this->lockFile = $lockFile;
        $this->output = $output;
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