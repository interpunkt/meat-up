<?php

namespace DL\MeatUp\Util;

class TwigUtil
{
    public static function getTwigEnvironment($meatUpDir)
    {
        return new \Twig_Environment(new \Twig_Loader_Filesystem(
            $meatUpDir . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'crud'
        ), array(
            'debug' => true,
            'cache' => false,
            'strict_variables' => true,
            'autoescape' => false,
        ));
    }
}