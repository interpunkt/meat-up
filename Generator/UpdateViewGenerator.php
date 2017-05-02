<?php

namespace DL\MeatUp\Generator;

use DL\MeatUp\Twig\MeatUpTwig;

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