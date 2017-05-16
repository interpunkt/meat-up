<?php

namespace Ip\MeatUp\Util;

use Symfony\Component\Filesystem\Filesystem;

final class LockFileUtil
{
    private $lockFile;
    private $errorMsg;
    private $rootDir;

    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
        $this->lockFile = $this->rootDir . DIRECTORY_SEPARATOR . 'meatup.lock';
        $this->errorMsg = '';
    }

    public function isSafeToWrite($fileName)
    {
        /**
         * if the file doesn't exist yet, it can always be created
         */
        if (!file_exists($fileName)) {
            return true;
        }

        /**
         * if no lock file exists, it is not safe to overwrite the target file
         */
        if (!file_exists($this->lockFile)) {
            $this->errorMsg = 'The target file exists, but not the lock file.';
            return false;
        }

        $lockFileJson = file_get_contents($this->lockFile);
        $lockFileArray = json_decode($lockFileJson, true);

        $relativeFileName = $this->getRelativePath($fileName);

        /**
         * if the file is not in the lock file, , it is not safe to overwrite the target file
         */
        if (!key_exists($relativeFileName, $lockFileArray)) {
            $this->errorMsg = 'The target file exists, but is not in the lock file.';
            return false;
        }

        $sha1LockFile = $lockFileArray[$relativeFileName];

        $sha1File = sha1_file($fileName);

        /**
         * if the file is identical to the one created by meat-up, it is safe to overwrite it
         */
        $isEqual = $sha1LockFile == $sha1File;

        if (!$isEqual) {
            $this->errorMsg = 'The target file has been modified since it has been created by meat-up.';
        }

        return $isEqual;
    }

    public function addToLockFile($fileName)
    {
        $lockFileArray = array();

        if (file_exists($this->lockFile)) {
            $lockFileJson = file_get_contents($this->lockFile);

            if (false === $lockFileJson) {
                throw new \RuntimeException('Can\'t read from lock file ' . $this->lockFile);
            }

            $lockFileArray = json_decode($lockFileJson, true);
        }

        $sha1File = sha1_file($fileName);

        $relativeFileName = $this->getRelativePath($fileName);

        $lockFileArray[$relativeFileName] = $sha1File;

        $lockFileString = json_encode($lockFileArray, JSON_PRETTY_PRINT);

        if (false === $lockFileString) {
            throw new \RuntimeException('Can\'t encode lock file array to json');
        }

        $writeResult = file_put_contents(
            $this->lockFile,
            $lockFileString
        );

        if (false === $writeResult) {
            throw new \RuntimeException('Can\'t write to lock file');
        }
    }

    public function getErrorMsg()
    {
        return $this->errorMsg.' It is not safe to overwrite it.';
    }

    private function getRelativePath($fileName)
    {
        $fs = new Filesystem();
        $relativeFileName = $fs->makePathRelative($fileName, $this->rootDir);

        /**
         * replace '\' with '/' for windows systems
         */
        $relativeFileName = str_replace('\\', '/', $relativeFileName);

        /**
         * remove trailing slashes
         */
        $relativeFileName = rtrim($relativeFileName, '/');

        return $relativeFileName;
    }
}