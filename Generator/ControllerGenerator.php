<?php

namespace DL\MeatUp\Generator;

use DL\MeatUp\Twig\MeatUpTwig;

class ControllerGenerator
{
    public static function generate($meatUpDir, $entityBundleName,
                                    $entityClassName, $entityBundleNameSpace)
    {

        $twig = MeatUpTwig::get($meatUpDir);

        $controller = $twig->render('controller.php.twig',
            array(
                'entityBundleNameSpace' => $entityBundleNameSpace,
                'name' => $entityClassName,
                // TODO get real plural
                'plural' => $entityClassName,
                'entityBundleName' => $entityBundleName,
            )
        );

        return $controller;
    }
}