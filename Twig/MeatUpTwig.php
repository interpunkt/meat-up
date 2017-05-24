<?php

namespace Ip\MeatUp\Twig;

class MeatUpTwig
{
    private $meatUpDir;

    public function __construct($meatUpDir)
    {
        $this->meatUpDir = $meatUpDir;
    }

    public function get()
    {
        $twigEnv = new \Twig_Environment(
            new \Twig_Loader_Filesystem(
                $this->meatUpDir . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'crud'
            ), array(
                'debug' => true,
                'cache' => false,
                'strict_variables' => true,
                'autoescape' => false,
            )
        );

        $twigEnv->addExtension(new IndexFilterExtension());
        $twigEnv->addExtension(new ManyToOneOrderByExtension());
        $twigEnv->addExtension(new ManyToOneChoiceLabelsExtension());

        return $twigEnv;
    }
}
