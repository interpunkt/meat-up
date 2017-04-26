<?php

namespace DL\MeatUp\Generator;

use DL\MeatUp\Util\FileUtil;
use DL\MeatUp\Util\ReflectionUtil;
use Symfony\Component\Console\Output\OutputInterface;

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
        if (!$this->generateFormTypeFile())
        {
            $this->output->writeln('Generating FormType file not successful');
            return false;
        }

        if (!$this->generateControllerFile())
        {
            $this->output->writeln('Generating Controller file not successful');
            return false;
        }

        if (!$this->generateViewFiles())
        {
            $this->output->writeln('Generating view files not successful');
            return false;
        }

        return true;
    }

    private function generateFormTypeFile()
    {
        $this->output->writeln('Generating FormType');
        $formType = FormGenerator::generate(
            $this->reflection,
            $this->meatUpDir,
            $this->entityBundleNameSpace
        );

        $formTypeDir = $this->bundleRootDir . DIRECTORY_SEPARATOR . 'Form' .
            DIRECTORY_SEPARATOR . 'Type' .DIRECTORY_SEPARATOR;

        $formTypeFile = $formTypeDir . $this->entityClassName . 'Type.php';

        if (FileUtil::writeToFile($formType, $formTypeFile) === false)
        {
            $this->output->writeln('Can\'t write to file ' . $formTypeFile);
            return false;
        }

        $this->output->writeln('Created file ' . $formTypeFile);
        return true;
    }

    private function generateControllerFile()
    {
        $this->output->writeln('Generating Controller');
        $controller = ControllerGenerator::generate(
            $this->meatUpDir,
            $this->entityBundleName,
            $this->entityClassName,
            $this->entityBundleNameSpace
        );

        $controllerFile = $this->bundleRootDir . DIRECTORY_SEPARATOR
            . 'Controller' . DIRECTORY_SEPARATOR . $this->entityClassName . 'Controller.php';

        if (FileUtil::writeToFile($controller, $controllerFile) === false)
        {
            $this->output->writeln('Can\'t write to file ' . $controllerFile);
            return false;
        }

        $this->output->writeln('Created file ' . $controllerFile);
        return true;
    }

    private function generateViewFiles()
    {
        $viewDir = $this->appDir . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'views'
            . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . $this->entityClassName;

        if (!FileUtil::createDirectory($viewDir)) {
            $this->output->writeln('Can\'t create directory ' . $viewDir);
            return false;
        }

        if (!$this->generateIndexViewFile($viewDir))
        {
            $this->output->writeln('Generating index view file not successful');
            return false;
        }

        if (!$this->generateInsertViewFile($viewDir))
        {
            $this->output->writeln('Generating index view file not successful');
            return false;
        }

        if (!$this->generateUpdateViewFile($viewDir))
        {
            $this->output->writeln('Generating index view file not successful');
            return false;
        }

        return true;
    }

    private function generateIndexViewFile($viewDir)
    {
        $this->output->writeln('Generating index view');
        $indexView = IndexViewGenerator::generate(
            $this->reflection,
            $this->meatUpDir,
            $this->entityClassName
        );

        $indexViewFile = $viewDir . DIRECTORY_SEPARATOR . 'index.html.twig';

        if (FileUtil::writeToFile($indexView, $indexViewFile) === false) {
            $this->output->writeln('Can\'t write to file ' . $indexViewFile);
            return false;
        }

        $this->output->writeln('Created file ' . $indexViewFile);
        return true;
    }

    private function generateInsertViewFile($viewDir)
    {
        $this->output->writeln('Generating insert view');
        $indexView = InsertViewGenerator::generate(
            $this->meatUpDir,
            $this->entityClassName
        );

        $insertViewFile = $viewDir . DIRECTORY_SEPARATOR . 'insert.html.twig';

        if (FileUtil::writeToFile($indexView, $insertViewFile) === false) {
            $this->output->writeln('Can\'t write to file ' . $insertViewFile);
            return false;
        }

        $this->output->writeln('Created file ' . $insertViewFile);
        return true;
    }

    private function generateUpdateViewFile($viewDir)
    {
        $this->output->writeln('Generating update view');
        $updateView = UpdateViewGenerator::generate(
            $this->meatUpDir,
            $this->entityClassName
        );

        $updateViewFile = $viewDir . DIRECTORY_SEPARATOR . 'update.html.twig';

        if (FileUtil::writeToFile($updateView, $updateViewFile) === false) {
            $this->output->writeln('Can\'t write to file ' . $updateViewFile);
            return false;
        }

        $this->output->writeln('Created file ' . $updateViewFile);
        return true;
    }
}