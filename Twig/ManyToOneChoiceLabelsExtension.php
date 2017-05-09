<?php


namespace Ip\MeatUp\Twig;


class ManyToOneChoiceLabelsExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('manyToOneChoiceLabels', array($this, 'manyToOneChoiceLabelsFilter')),
        );
    }

    public function manyToOneChoiceLabelsFilter($choiceLabels)
    {
        $labelsLength = count($choiceLabels) ;

        if ($labelsLength == 0) {
            throw new \LogicException('The parameter for manyToOneChoiceLabels must not be empty');
        }
        elseif ($labelsLength == 1) {
            return '\'' . $choiceLabels[0] . '\'';
        }

        $labelExpression = 'function ($item) {' . PHP_EOL;

        // add white spaces for correct indentation
        for ($i = 0; $i < 20; ++$i) {
            $labelExpression .= ' ';
        }

        $labelExpression .= 'return';

        for ($i = 0; $i < $labelsLength; ++$i) {
            $labelExpression .= ' $item->get' . ucfirst($choiceLabels[$i]) .'()';

            if ($i != $labelsLength - 1) {
                $labelExpression .= ' . \' \' .';
            }
        }

        $labelExpression .= ';' . PHP_EOL;

        // add white spaces for correct indentation
        for ($i = 0; $i < 16; ++$i) {
            $labelExpression .= ' ';
        }

        $labelExpression .= '}';

        return $labelExpression;
    }
}