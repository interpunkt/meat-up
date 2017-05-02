<?php

namespace Ip\MeatUp\Generator;

use Ip\MeatUp\Util\TwigUtil;

class UpdateViewGenerator
{
    public static function generate($meatUpDir, $entityClassName)
    {
        $twig = TwigUtil::getTwigEnvironment($meatUpDir);

        $indexView = $twig->render('views/update.html.twig.twig',
            array(
                'name' => $entityClassName,
            )
        );

        return $indexView;
    }
}