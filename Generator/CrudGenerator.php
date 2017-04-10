<?php

namespace DL\MeatUpBundle\Generator;

use DL\MeatUpBundle\Util\ReflectionUtil;
use Symfony\Component\Console\Output\OutputInterface;

final class CrudGenerator
{
    private $reflection;
    private $appDir;
    private $meatUpDir;
    private $entityBundleNameSpace;
    private $bundleRootDir;
    private $output;
    private $entityClassName;

    public function __construct($className, $appDir, $meatUpDir, $entityBundleNameSpace, $bundleRootDir,
                                OutputInterface $output)
    {
        $this->reflection = new ReflectionUtil($className);
        $this->entityClassName = $this->reflection->getClassShortName();
        $this->appDir = $appDir;
        $this->meatUpDir = $meatUpDir;
        $this->entityBundleNameSpace = $entityBundleNameSpace;
        $this->bundleRootDir = $bundleRootDir;
        $this->output = $output;
    }

    public function generate()
    {
        $this->output->writeln('Generating FormType');
        $formType = FormGenerator::generate($this->reflection, $this->meatUpDir, $this->entityBundleNameSpace);

        $formTypeDir = $this->bundleRootDir . DIRECTORY_SEPARATOR . 'Form' .
            DIRECTORY_SEPARATOR . 'Type' .DIRECTORY_SEPARATOR;

        $formTypeFile = $formTypeDir . $this->entityClassName . 'Type.php';

        if ($this->writeToFile($formType, $formTypeFile) === false) {
            $this->output->writeln('Can\'t write to file ' . $formTypeFile);
        }
    }

    private function writeToFile($content, $filePath) {
        $fp = fopen($filePath, 'w');

        if ($fp === false) {
            return false;
        }

        return fwrite($fp, $content) !== false;
    }
}