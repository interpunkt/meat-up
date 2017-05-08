<?php

namespace Ip\MeatUp\Twig;

class MeatUpTwig
{
    public static function get($meatUpDir)
    {
        $twigEnv = new \Twig_Environment(
            new \Twig_Loader_Filesystem(
                $meatUpDir . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'crud'
            ), array(
                'debug' => true,
                'cache' => false,
                'strict_variables' => true,
                'autoescape' => false,
            )
        );

        $twigEnv->addExtension(new IndexFilterExtension());
        $twigEnv->addExtension(new ManyToOneOrderByExtension());

        return $twigEnv;
    }
}