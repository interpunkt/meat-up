<?php

namespace Ip\MeatUp\Generator;

use Doctrine\Common\Annotations\AnnotationReader;
use Ip\MeatUp\Util\AnnotationUtil;
use Ip\MeatUp\Util\FileUtil;
use Ip\MeatUp\Util\LockFileUtil;
use ReflectionClass;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class CrudGeneratorBuilder
{
    private $className;
    /**
     * @var Container
     */
    private $container;
    /**
     * @var OutputInterface
     */
    private $output;
    private $hasForce;

    // forbid use of constructor
    private function __construct()
    {
    }

    public static function create()
    {
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
     * @param bool $hasForce
     * @return CrudGeneratorBuilder
     */
    public function setHasForce($hasForce)
    {
        $this->hasForce = $hasForce;

        return $this;
    }

    /**
     * @return CrudGenerator | false
     */
    public function build()
    {
        $this->checkProperties();

        $appDir = $this->container->getParameter('kernel.root_dir');
        $this->output->writeln('App dir: ' . $appDir);

        $meatUpDir = realpath(dirname(__DIR__));

        $bundles = $this->container
            ->get('kernel')
            ->getBundles();

        $entityBundleNameSpace = null;
        $bundleRootDir = null;
        $entityBundleName = null;

        /**
         * @var BundleInterface $bundle
         */
        foreach ($bundles as $bundle) {
            if (strpos($this->className, $bundle->getNamespace(), 0) === 0) {
                $entityBundleNameSpace = $bundle->getNamespace();
                $bundleRootDir = $bundle->getPath();
                $entityBundleName = $bundle->getName();
                break;
            }
        }

        if (is_null($bundleRootDir) || is_null($entityBundleNameSpace)) {
            $this->output->writeln('Can\'t find bundle root dir');
            return false;
        }

        if (is_null($entityBundleNameSpace)) {
            $this->output->writeln('Can\'t find namespace of entity');
            return false;
        }

        if (is_null($entityBundleName)) {
            $this->output->writeln('Can\'t find bundle name of entity');
            return false;
        }

        // use the root directory of the project (one level up of app directory)
        $lockFileUtil = new LockFileUtil(
            dirname($appDir),
            $this->hasForce
        );

        $fileUtil = new FileUtil(
            $lockFileUtil,
            $this->output
        );

        // TODO use cached reader
        $annotationReader = new AnnotationReader();
        $reflectedClass = new ReflectionClass($this->className);
        $annotationUtil = new AnnotationUtil($annotationReader, $reflectedClass);

        return new CrudGenerator(
            $annotationUtil,
            $appDir,
            $meatUpDir,
            $entityBundleNameSpace,
            $bundleRootDir,
            $entityBundleName,
            $this->output,
            $fileUtil
        );
    }

    private function checkProperties()
    {
        $this->checkProperty($this->className, 'className');
        $this->checkProperty($this->container, 'container');
        $this->checkProperty($this->output, 'output');
        $this->checkProperty($this->hasForce, 'hasForce');
    }

    private function checkProperty($property, $propertyName)
    {
        if (is_null($property)) {
            throw new \BadMethodCallException('CrudGeneratorBuilder: called build without setting ' . $propertyName);
        }
    }
}
