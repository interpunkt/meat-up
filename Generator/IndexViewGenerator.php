<?php

namespace DL\MeatUp\Generator;

use DL\MeatUp\Util\TwigUtil;

final class IndexViewGenerator
{
    public static function generate($meatUpDir, $entityClassName)
    {
        $twig = TwigUtil::getTwigEnvironment($meatUpDir);

        $indexView = $twig->render('views/index.html.twig.twig',
            array(
                'name' => $entityClassName,
            )
        );

        return $indexView;
    }
}