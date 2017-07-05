<?php

namespace Ip\MeatUp\Generator;

use Ip\MeatUp\Util\AnnotationUtil;
use Ip\MeatUp\Twig\MeatUpTwig;

class IndexViewGenerator
{
    private $annotation;
    private $meatUpTwig;
    private $entityClassName;

    public function __construct(MeatUpTwig $meatUpTwig, AnnotationUtil $annotation, $entityClassName)
    {
        $this->meatUpTwig = $meatUpTwig;
        $this->annotation = $annotation;
        $this->entityClassName = $entityClassName;
    }

    public function generate()
    {
        $indexProperties = array();
        $indexPropertyLabels = array();
        $indexPropertyFilters = array();
        $indexPropertyFilterArguments = array();

        $sortablePositionName = null;
        $doctrineIndexName = null;

        /**
         * @var \ReflectionProperty $property
         */
        foreach ($this->annotation->getProperties() as $property) {
            if ($this->annotation->has('OnIndexPage', $property)) {
                $propertyName = $property->getName();
                $indexProperties[] = $propertyName;

                $indexPropertyLabel = $this->annotation->get('OnIndexPage', 'label', $property);
                $indexPropertyLabels[] = !empty($indexPropertyLabel) ? $indexPropertyLabel : ucfirst($propertyName);

                $indexPropertyFilter = $this->annotation->get('OnIndexPage', 'filter', $property);
                $indexPropertyFilters[]  = !empty($indexPropertyFilter) ? $indexPropertyFilter : '';

                $indexPropertyFilterArgument = $this->annotation->get('OnIndexPage', 'filterParameters', $property);
                $indexPropertyFilterArguments[] =
                    !empty($indexPropertyFilterArgument) ? $indexPropertyFilterArgument : '';
            }

            if ($this->annotation->has('SortablePosition', $property)) {
                $sortablePositionName = $property->getName();
            }

            if ($this->annotation->has('Id', $property)) {
                $doctrineIndexName = $property->getName();
            }
        }

        $twig = $this->meatUpTwig->get();

        $indexView = $twig->render('views/index.html.twig.twig',
            array(
                'name' => $this->entityClassName,
                'indexPropertyLabels' => $indexPropertyLabels,
                'indexPropertyNames' => $indexProperties,
                'indexPropertyFilters' => $indexPropertyFilters,
                'indexPropertyFilterArguments' => $indexPropertyFilterArguments,
                'sortablePositionName' => $sortablePositionName,
                'doctrineIndexName' => $doctrineIndexName,
            )
        );

        return $indexView;
    }
}
