<?php

namespace Ip\MeatUp\Generator;

use Ip\MeatUp\Twig\MeatUpTwig;

class ControllerGenerator
{
    private $meatUpTwig;
    private $entityBundleName;
    private $entityClassName;
    private $entityBundleNameSpace;

    public function __construct(MeatUpTwig $meatUpTwig, $entityBundleName, $entityClassName, $entityBundleNameSpace)
    {
        $this->meatUpTwig = $meatUpTwig;
        $this->entityBundleName = $entityBundleName;
        $this->entityClassName = $entityClassName;
        $this->entityBundleNameSpace = $entityBundleNameSpace;
    }

    public function generate()
    {
        $twig = $this->meatUpTwig->get();

        $controller = $twig->render('controller.php.twig',
            array(
                'entityBundleNameSpace' => $this->entityBundleNameSpace,
                'name' => $this->entityClassName,
                // TODO get real plural
                'plural' => $this->entityClassName,
                'entityBundleName' => $this->entityBundleName,
            )
        );

        return $controller;
    }
}
