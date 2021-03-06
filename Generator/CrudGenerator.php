<?php

namespace Ip\MeatUp\Generator;

use Ip\MeatUp\Twig\MeatUpTwig;
use Ip\MeatUp\Util\FileUtil;
use Ip\MeatUp\Util\AnnotationUtil;
use Ip\MeatUp\Util\FormImportUtil;
use Symfony\Component\Console\Output\OutputInterface;

class CrudGenerator
{
    private $annotationUtil;
    private $appDir;
    private $meatUpDir;
    private $bundleRootDir;
    private $output;
    private $entityBundleName;
    private $entityBundleNameSpace;
    private $entityClassName;
    private $fileUtil;

    /**
     * @param AnnotationUtil $annotationUtil
     * @param string $appDir
     * @param string $meatUpDir
     * @param string $entityBundleNameSpace
     * @param string $bundleRootDir
     * @param string $entityBundleName
     * @param OutputInterface $output
     * @param FileUtil $fileUtil
     */
    public function __construct(
        AnnotationUtil $annotationUtil,
        $appDir,
        $meatUpDir,
        $entityBundleNameSpace,
        $bundleRootDir,
        $entityBundleName,
        OutputInterface $output,
        FileUtil $fileUtil
    ) {
        $this->annotationUtil = $annotationUtil;
        $this->entityClassName = $this->annotationUtil->getClassShortName();
        $this->appDir = $appDir;
        $this->meatUpDir = $meatUpDir;
        $this->entityBundleNameSpace = $entityBundleNameSpace;
        $this->bundleRootDir = $bundleRootDir;
        $this->entityBundleName = $entityBundleName;
        $this->output = $output;
        $this->fileUtil = $fileUtil;
    }

    public function generate()
    {
        if (!$this->generateFormTypeFile()) {
            $this->output->writeln('Generating FormType file not successful');
            return false;
        }

        if (!$this->generateControllerFile()) {
            $this->output->writeln('Generating Controller file not successful');
            return false;
        }

        if (!$this->generateViewFiles()) {
            $this->output->writeln('Generating view files not successful');
            return false;
        }

        return true;
    }

    private function generateFormTypeFile()
    {
        $this->output->writeln('Generating FormType');
        $formGenerator = new FormGenerator(
            new MeatUpTwig($this->meatUpDir),
            $this->annotationUtil,
            new FormImportUtil(),
            $this->entityBundleNameSpace
        );
        $formType = $formGenerator->generate();

        $formTypeDir = $this->bundleRootDir . DIRECTORY_SEPARATOR . 'Form' .
            DIRECTORY_SEPARATOR . 'Type' .DIRECTORY_SEPARATOR;

        $formTypeFile = $formTypeDir . $this->entityClassName . 'Type.php';
        

        if ($this->fileUtil->writeToFile($formType, $formTypeFile) === false) {
            return false;
        }

        $this->output->writeln('Created file ' . $formTypeFile);
        return true;
    }

    private function generateControllerFile()
    {
        $this->output->writeln('Generating Controller');

        $controllerGenerator = new ControllerGenerator(
            new MeatUpTwig($this->meatUpDir),
            $this->annotationUtil,
            $this->entityBundleName,
            $this->entityClassName,
            $this->entityBundleNameSpace
        );

        $controller = $controllerGenerator->generate();

        $controllerFile = $this->bundleRootDir . DIRECTORY_SEPARATOR
            . 'Controller' . DIRECTORY_SEPARATOR . $this->entityClassName . 'Controller.php';

        if ($this->fileUtil->writeToFile($controller, $controllerFile) === false) {
            return false;
        }

        $this->output->writeln('Created file ' . $controllerFile);
        return true;
    }

    private function generateViewFiles()
    {
        $viewDir = $this->appDir . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'views'
            . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . $this->entityClassName;

        if (!$this->fileUtil->createDirectory($viewDir)) {
            $this->output->writeln('Can\'t create directory ' . $viewDir);
            return false;
        }

        if (!$this->generateIndexViewFile($viewDir)) {
            $this->output->writeln('Generating index view file not successful');
            return false;
        }

        if (!$this->generateInsertViewFile($viewDir)) {
            $this->output->writeln('Generating index view file not successful');
            return false;
        }

        if (!$this->generateUpdateViewFile($viewDir)) {
            $this->output->writeln('Generating index view file not successful');
            return false;
        }

        return true;
    }

    private function generateIndexViewFile($viewDir)
    {
        $this->output->writeln('Generating index view');

        $indexViewGenerator = new IndexViewGenerator(
            new MeatUpTwig($this->meatUpDir),
            $this->annotationUtil,
            $this->entityClassName
        );

        $indexView = $indexViewGenerator->generate();

        $indexViewFile = $viewDir . DIRECTORY_SEPARATOR . 'index.html.twig';

        if ($this->fileUtil->writeToFile($indexView, $indexViewFile) === false) {
            return false;
        }

        $this->output->writeln('Created file ' . $indexViewFile);
        return true;
    }

    private function generateInsertViewFile($viewDir)
    {
        $this->output->writeln('Generating insert view');

        $insertViewGenerator = new InsertViewGenerator(
            new MeatUpTwig($this->meatUpDir),
            $this->entityClassName
        );

        $indexView = $insertViewGenerator->generate();

        $insertViewFile = $viewDir . DIRECTORY_SEPARATOR . 'insert.html.twig';

        if ($this->fileUtil->writeToFile($indexView, $insertViewFile) === false) {
            return false;
        }

        $this->output->writeln('Created file ' . $insertViewFile);
        return true;
    }

    private function generateUpdateViewFile($viewDir)
    {
        $this->output->writeln('Generating update view');

        $updateViewGenerator = new UpdateViewGenerator(
            new MeatUpTwig($this->meatUpDir),
            $this->entityClassName
        );

        $updateView = $updateViewGenerator->generate();

        $updateViewFile = $viewDir . DIRECTORY_SEPARATOR . 'update.html.twig';

        if ($this->fileUtil->writeToFile($updateView, $updateViewFile) === false) {
            return false;
        }

        $this->output->writeln('Created file ' . $updateViewFile);
        return true;
    }
}
