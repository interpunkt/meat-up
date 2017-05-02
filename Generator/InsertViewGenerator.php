<?php

namespace DL\MeatUp\Generator;

use DL\MeatUp\Twig\MeatUpTwig;

class InsertViewGenerator
{
    public static function generate($meatUpDir, $entityClassName)
    {
        $twig = MeatUpTwig::get($meatUpDir);

        $indexView = $twig->render('views/insert.html.twig.twig',
            array(
                'name' => $entityClassName,
            )
        );

        return $indexView;
    }
}