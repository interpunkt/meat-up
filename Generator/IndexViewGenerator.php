<?php

namespace DL\MeatUp\Generator;

use DL\MeatUp\Util\ReflectionUtil;
use DL\MeatUp\Util\TwigUtil;

final class IndexViewGenerator
{
    public static function generate(ReflectionUtil $reflection, $meatUpDir, $entityClassName)
    {
        $indexPropertyLabels = array();
        $indexProperties = array();

        foreach ($reflection->getProperties() as $property)
        {
            if ($reflection->hasOnIndexPage($property))
            {
                $propertyName = $property->getName();

                $indexPropertyLabel = $reflection->getOnIndexPageLabel($property);

                if ($indexPropertyLabel === false) {
                    $indexPropertyLabels[] = ucfirst($propertyName);
                }
                else {
                    $indexPropertyLabels[] = $indexPropertyLabel;
                }

                $indexProperties[] = $propertyName;

            }
        }

        $twig = TwigUtil::getTwigEnvironment($meatUpDir);

        $indexView = $twig->render('views/index.html.twig.twig',
            array(
                'name' => $entityClassName,
                'indexPropertyLabels' => $indexPropertyLabels,
                'indexPropertyNames' => $indexProperties
            )
        );

        return $indexView;
    }
}