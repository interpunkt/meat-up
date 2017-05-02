<?php

namespace Ip\MeatUp\Generator;

use Ip\MeatUp\Util\TwigUtil;

class InsertViewGenerator
{
    public static function generate($meatUpDir, $entityClassName)
    {
        $twig = TwigUtil::getTwigEnvironment($meatUpDir);

        $indexView = $twig->render('views/insert.html.twig.twig',
            array(
                'name' => $entityClassName,
            )
        );

        return $indexView;
    }
}