<?php

namespace DL\MeatUp\Generator;

use DL\MeatUp\Util\ReflectionUtil;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class CrudGenerator
{
    private $reflection;
    private $appDir;
    private $meatUpDir;
    private $bundleRootDir;
    private $output;
    private $entityBundleName;
    private $entityBundleNameSpace;
    private $entityClassName;

    public function __construct($className, $appDir, $meatUpDir, $entityBundleNameSpace, $bundleRootDir,
                                $entityBundleName, OutputInterface $output)
    {
        $this->reflection = new ReflectionUtil($className);
        $this->entityClassName = $this->reflection->getClassShortName();
        $this->appDir = $appDir;
        $this->meatUpDir = $meatUpDir;
        $this->entityBundleNameSpace = $entityBundleNameSpace;
        $this->bundleRootDir = $bundleRootDir;
        $this->entityBundleName = $entityBundleName;
        $this->output = $output;
    }

    public function generate()
    {
        // Generate form type
        $this->output->writeln('Generating FormType');
        $formType = FormGenerator::generate($this->reflection, $this->meatUpDir, $this->entityBundleNameSpace);

        $formTypeDir = $this->bundleRootDir . DIRECTORY_SEPARATOR . 'Form' .
            DIRECTORY_SEPARATOR . 'Type' .DIRECTORY_SEPARATOR;

        $formTypeFile = $formTypeDir . $this->entityClassName . 'Type.php';

        if ($this->writeToFile($formType, $formTypeFile) === false) {
            $this->output->writeln('Can\'t write to file ' . $formTypeFile);
            return false;
        }

        $this->output->writeln('Created file ' . $formTypeFile);

        // Generate Controller
        $this->output->writeln('Generating Controller');
        $controller = ControllerGenerator::generate($this->meatUpDir, $this->entityBundleName,
            $this->entityClassName, $this->entityBundleNameSpace);

        $controllerFile = $this->bundleRootDir . DIRECTORY_SEPARATOR
            . 'Controller' . DIRECTORY_SEPARATOR . $this->entityClassName . 'Controller.php';

        if ($this->writeToFile($controller, $controllerFile) === false) {
            $this->output->writeln('Can\'t write to file ' . $formTypeFile);
            return false;
        }

        return true;
    }

    private function writeToFile($content, $filePath) {
        $fp = fopen($filePath, 'w');

        if ($fp === false) {
            return false;
        }

        return fwrite($fp, $content) !== false;
    }
}