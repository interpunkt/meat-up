<?php

namespace Ip\MeatUp\Twig;

class IndexFilterExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('indexFilter', array($this, 'indexFilter')),
        );
    }

    public function indexFilter($filter, $filterParameter = [])
    {
        if (empty($filter)) {
            return '';
        }

        $filterExpression = ' | ' . $filter;

        $filterParameterLength = count($filterParameter);

        for ($i = 0; $i < $filterParameterLength; ++$i) {
            if ($i == 0) {
                $filterExpression .= '(';
            }

            $filterExpression .= "'$filterParameter[$i]'";

            if ($i == $filterParameterLength - 1) {
                $filterExpression .= ') ';
            } else {
                $filterExpression .= ', ';
            }
        }

        return $filterExpression;
    }
}
