<?php
declare(strict_types=1);

namespace Ip\MeatUp\Tests\Twig;

use Ip\MeatUp\Twig\ManyToOneChoiceLabelsExtension;
use LogicException;
use PHPUnit\Framework\TestCase;
use Twig_SimpleFilter;

final class ManyToOneChoiceLabelsExtensionTest extends TestCase
{
    /**
     * @var ManyToOneChoiceLabelsExtension
     */
    private $twigExtension;

    protected function setUp()
    {
        $this->twigExtension = new ManyToOneChoiceLabelsExtension();
    }

    public function testCorrectFilterName()
    {
        /**
         * @var Twig_SimpleFilter[] $filters
         */
        $filters = $this->twigExtension->getFilters();

        $this->assertEquals(
            1,
            count($filters),
            'ManyToOneChoiceLabelsExtension should return exactly one filter'
        );

        $this->assertEquals(
            'manyToOneChoiceLabels',
            $filters[0]->getName(),
            'The filter of ManyToOneChoiceLabelsExtension has to be named manyToOneChoiceLabels'
        );
    }

    public function testEmptyParameter()
    {
        $this->expectException(LogicException::class);
        $this->twigExtension->manyToOneChoiceLabelsFilter([]);
    }

    public function testWithOneParameter()
    {
        $this->assertEquals(
            '\'name\'',
            $this->twigExtension->manyToOneChoiceLabelsFilter(['name']),
            'manyToOneChoiceLabelsFilter returned the wrong expression with one parameter'
        );
    }

    public function testWithTwoParameter()
    {
        $this->assertEquals(
            'function ($item) {' . PHP_EOL .
                        '                    return $item->getName() . \' \' . $item->getName2();' . PHP_EOL .
                    '                }',
            $this->twigExtension->manyToOneChoiceLabelsFilter(['name', 'name2']),
            'manyToOneChoiceLabelsFilter returned the wrong expression with one parameter'
        );
    }
}
