<?php

namespace Ip\MeatUp\Generator;

use Ip\MeatUp\Twig\MeatUpTwig;

class InsertViewGenerator
{
    private $meatUpTwig;
    private $entityClassName;

    public function __construct(MeatUpTwig $meatUpTwig, $entityClassName)
    {
        $this->meatUpTwig = $meatUpTwig;
        $this->entityClassName = $entityClassName;
    }

    public function generate()
    {
        $twig = $this->meatUpTwig->get();

        $indexView = $twig->render('views/insert.html.twig.twig',
            array(
                'name' => $this->entityClassName,
            )
        );

        return $indexView;
    }
}
