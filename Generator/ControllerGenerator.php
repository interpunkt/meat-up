<?php

namespace Ip\MeatUp\Generator;

use Ip\MeatUp\Twig\MeatUpTwig;
use Ip\MeatUp\Util\AnnotationUtil;

class ControllerGenerator
{
    private $meatUpTwig;
    private $annotation;
    private $entityBundleName;
    private $entityClassName;
    private $entityBundleNameSpace;

    public function __construct(
        MeatUpTwig $meatUpTwig,
        AnnotationUtil $annotation,
        $entityBundleName,
        $entityClassName,
        $entityBundleNameSpace
    ) {
        $this->meatUpTwig = $meatUpTwig;
        $this->annotation = $annotation;
        $this->entityBundleName = $entityBundleName;
        $this->entityClassName = $entityClassName;
        $this->entityBundleNameSpace = $entityBundleNameSpace;
    }

    public function generate()
    {
        $sortablePositionName = null;
        $doctrineIndexName = null;

        /**
         * @var \ReflectionProperty $property
         */
        foreach ($this->annotation->getProperties() as $property) {
            if ($this->annotation->has('SortablePosition', $property)) {
                $sortablePositionName = $property->getName();
            }
            if ($this->annotation->has('Id', $property)) {
                $doctrineIndexName = $property->getName();
            }
        }

        $twig = $this->meatUpTwig->get();

        $controller = $twig->render('controller.php.twig',
            array(
                'entityBundleNameSpace' => $this->entityBundleNameSpace,
                'name' => $this->entityClassName,
                // TODO get real plural
                'plural' => $this->entityClassName,
                'entityBundleName' => $this->entityBundleName,
                'sortablePositionName' => $sortablePositionName,
                'doctrineIndexName' => $doctrineIndexName,
            )
        );

        return $controller;
    }
}
