<?php

namespace Ip\MeatUp\Generator;

use Ip\MeatUp\Util\AnnotationUtil;
use Ip\MeatUp\Twig\MeatUpTwig;

final class IndexViewGenerator
{
    public static function generate(AnnotationUtil $annotation, $meatUpDir, $entityClassName)
    {
        $indexProperties = array();
        $indexPropertyLabels = array();
        $indexPropertyFilters = array();
        $indexPropertyFilterArguments = array();

        foreach ($annotation->getProperties() as $property)
        {
            if ($annotation->hasOnIndexPage($property))
            {
                $propertyName = $property->getName();
                $indexProperties[] = $propertyName;

                $indexPropertyLabel = $annotation->getOnIndexPageLabel($property);
                $indexPropertyLabels[] = !empty($indexPropertyLabel) ? $indexPropertyLabel : ucfirst($propertyName);

                $indexPropertyFilter = $annotation->getOnIndexPageFilter($property);
                $indexPropertyFilters[]  = !empty($indexPropertyFilter) ? $indexPropertyFilter : '';

                $indexPropertyFilterArgument = $annotation->getOnIndexPageFilterParameters($property);
                $indexPropertyFilterArguments[]  = !empty($indexPropertyFilterArgument) ? $indexPropertyFilterArgument : '';
            }
        }

        $twig = MeatUpTwig::get($meatUpDir);

        $indexView = $twig->render('views/index.html.twig.twig',
            array(
                'name' => $entityClassName,
                'indexPropertyLabels' => $indexPropertyLabels,
                'indexPropertyNames' => $indexProperties,
                'indexPropertyFilters' => $indexPropertyFilters,
                'indexPropertyFilterArguments' => $indexPropertyFilterArguments
            )
        );

        return $indexView;
    }
}