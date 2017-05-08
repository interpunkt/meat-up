<?php

namespace Ip\MeatUp\Twig;

class ManyToOneOrderByExtension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('manyToOneOrderByFilter', array($this, 'manyToOneOrderByFilter')),
        );
    }

    public function manyToOneOrderByFilter($sortArray, $orderArray)
    {
        if (empty($sortArray) || empty($orderArray)) {
            return '';
        }

        if (count($sortArray) != count($orderArray)) {
            throw new \LogicException('manyToOneOrderBy always needs the same number of values for propertyNames and orderDirections');
        }

        $orderByExpression = '';

        $sortLength = count($sortArray);

        for ($i = 0; $i < $sortLength; ++$i) {
            if ($orderArray[$i] != 'ASC' && $orderArray[$i] != 'DESC') {
                throw new \BadMethodCallException('orderDirections of annotation ManyToOneOrderBy needs to be ASC or DESC');
            }

            if ($i == 0) {
                $orderByExpression .= '->orderBy(\'e.' . $sortArray[$i] . '\', \'' . $orderArray[$i] . '\')';
            }
            else {
                $orderByExpression .= '->addOrderBy(\'e.' . $sortArray[$i] . '\', \'' . $orderArray[$i] . '\')';
            }

            if ($i == $sortLength - 1) {
                $orderByExpression .= ';';
            }
            else {
                $orderByExpression .= PHP_EOL;
            }
        }

        return $orderByExpression;
    }
}