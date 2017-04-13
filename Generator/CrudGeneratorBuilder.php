<?php

namespace DL\MeatUp\Generator;

use DL\MeatUp\Util\ReflectionUtil;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class CrudGeneratorBuilder
{
    private $className = null;
    private $container = null;
    private $output = null;

    // forbid use of constructor
    private function __construct() {}

    public static function create() {
        return new CrudGeneratorBuilder();
    }

    /**
     * @param string $className
     * @return CrudGeneratorBuilder
     */
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * @param ContainerInterface $container
     * @return CrudGeneratorBuilder
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @param OutputInterface $output
     * @return CrudGeneratorBuilder
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * @return CrudGenerator | false
     */
    public function build() {
        $this->checkProperties();

        $this->reflection = new ReflectionUtil($this->className);

        $appDir = $this->container->getParameter('kernel.root_dir');
        $this->output->writeln('App dir: ' . $appDir);

        $meatUpDir = realpath(dirname(__FILE__, 2));

        $bundles = $this->container
            ->get('kernel')
            ->getBundles();

        $entityBundleNameSpace = null;
        $bundleRootDir = null;
        $entityBundleName = null;

        foreach ($bundles as $bundle) {
            if (strpos($this->className, $bundle->getNamespace(), 0) === 0) {
                $entityBundleNameSpace = $bundle->getNamespace();
                $bundleRootDir = $bundle->getPath();
                $entityBundleName = $bundle->getName();
                break;
            }
        }

        if (is_null($bundleRootDir) || is_null($entityBundleNameSpace) ) {
            $this->output->writeln('Can\'t find bundle root dir');
            return false;
        }

        if (is_null($entityBundleNameSpace) ) {
            $this->output->writeln('Can\'t find namespace of entity');
            return false;
        }

        if (is_null($entityBundleName) ) {
            $this->output->writeln('Can\'t find bundle name of entity');
            return false;
        }

        return new CrudGenerator(
            $this->className,
            $appDir,
            $meatUpDir,
            $entityBundleNameSpace,
            $bundleRootDir,
            $entityBundleName,
            $this->output
        );
    }

    private function checkProperties() {
        $this->checkProperty($this->className, 'className');
        $this->checkProperty($this->container, 'container');
        $this->checkProperty($this->output, 'output');
    }

    private function checkProperty($property, $propertyName)
    {
        if (is_null($property)) {
            throw new \BadMethodCallException('CrudGeneratorBuilder: called build without setting ' . $propertyName);
        }
    }
}