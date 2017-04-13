<?php

namespace DL\MeatUp\Generator;

use DL\MeatUp\Util\TwigUtil;

class ControllerGenerator
{
    public static function generate($meatUpDir, $entityBundleName,
                                    $entityClassName, $entityBundleNameSpace) {

        $twig = TwigUtil::getTwigEnvironment($meatUpDir);

        $controller = $twig->render('controller.php.twig',
            array(
                '$entityBundleNameSpace' => $entityBundleNameSpace,
                'name' => $entityClassName,
                // TODO get real plural
                'entityBundleName' => $entityBundleName,
            )
        );

        return $controller;
    }
}