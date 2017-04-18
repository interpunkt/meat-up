<?php

namespace DL\MeatUp\Generator;

use DL\MeatUp\Util\TwigUtil;

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