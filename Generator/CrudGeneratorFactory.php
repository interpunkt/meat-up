<?php

namespace DL\MeatUpBundle\Generator;

use Symfony\Component\Console\Output\OutputInterface;

final class CrudGeneratorFactory
{
    private $className = null;
    private $appDir = null;
    private $meatUpDir = null;
    private $entityBundleNameSpace = null;
    private $bundleRootDir = null;
    private $output = null;

    // forbid use of constructor
    private function __construct() {}

    public static function create() {
        return new CrudGeneratorFactory();
    }

    /**
     * @param stringTest $className
     * @return CrudGeneratorFactory
     */
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * @param stringTest $appDir
     * @return CrudGeneratorFactory
     */
    public function setAppDir($appDir)
    {
        $this->appDir = $appDir;

        return $this;
    }

    /**
     * @param stringTest $meatUpDir
     * @return CrudGeneratorFactory
     */
    public function setMeatUpDir($meatUpDir)
    {
        $this->meatUpDir = $meatUpDir;

        return $this;
    }

    /**
     * @param stringTest $entityBundleNameSpace
     * @return CrudGeneratorFactory
     */
    public function setEntityBundleNameSpace($entityBundleNameSpace)
    {
        $this->entityBundleNameSpace = $entityBundleNameSpace;

        return $this;
    }

    /**
     * @param stringTest $bundleRootDir
     * @return CrudGeneratorFactory
     */
    public function setBundleRootDir($bundleRootDir)
    {
        $this->bundleRootDir = $bundleRootDir;

        return $this;
    }

    /**
     * @param OutputInterface $output
     * @return CrudGeneratorFactory
     */
    public function setOutput($output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * @return CrudGenerator
     */
    public function build() {
        $this->checkProperties();

        return new CrudGenerator(
            $this->className,
            $this->appDir,
            $this->meatUpDir,
            $this->entityBundleNameSpace,
            $this->bundleRootDir,
            $this->output
        );
    }

    private function checkProperties() {
        $this->checkPropertiy($this->className, 'className');
        $this->checkPropertiy($this->appDir, 'appDir');
        $this->checkPropertiy($this->meatUpDir, 'meatUpDir');
        $this->checkPropertiy($this->entityBundleNameSpace, 'bundleNameSpace');
        $this->checkPropertiy($this->bundleRootDir, 'bundleRootDir');
        $this->checkPropertiy($this->output, 'output');
    }

    private function checkPropertiy($property, $propertyName)
    {
        if (is_null($property)) {
            throw new \BadMethodCallException('CrudGeneratorBuilder: called build without setting ' . $propertyName);
        }
    }
}