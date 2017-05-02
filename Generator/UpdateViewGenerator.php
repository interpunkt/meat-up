<?php

namespace Ip\MeatUp\Generator;

use Ip\MeatUp\Twig\MeatUpTwig;

class UpdateViewGenerator
{
    public static function generate($meatUpDir, $entityClassName)
    {
        $twig = MeatUpTwig::get($meatUpDir);

        $indexView = $twig->render('views/update.html.twig.twig',
            array(
                'name' => $entityClassName,
            )
        );

        return $indexView;
    }
}