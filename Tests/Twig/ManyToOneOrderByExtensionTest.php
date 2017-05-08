<?php
declare(strict_types=1);

namespace Ip\MeatUp\Tests\Twig;

use BadMethodCallException;
use Ip\MeatUp\Twig\ManyToOneOrderByExtension;
use LogicException;
use PHPUnit\Framework\TestCase;

final class ManyToOneOrderByExtensionTest extends TestCase
{
    /**
     * @var ManyToOneOrderByExtension
     */
    private $twigExtension;

    protected function setUp()
    {
        $this->twigExtension = new ManyToOneOrderByExtension();
    }

    public function testCorrectFilterName()
    {
        $filters = $this->twigExtension->getFilters();

        $this->assertEquals(
            1,
            count($filters),
            'ManyToOneOrderByExtension should return exactly one filter'
        );

        $this->assertEquals(
            'manyToOneOrderByFilter',
            $filters[0]->getName(),
            'The filter of ManyToOneOrderByExtension should be name manyToOneOrderByFilter'
        );
    }

    public function testEmptyParameter()
    {
        $this->assertEquals(
            '',
            $this->twigExtension->manyToOneOrderByFilter([], []),
            'With empty parameters manyToOneOrderByFilter should return an empty string'
        );
    }

    public function testNumberOfParametersNotEqual()
    {
        $this->expectException(LogicException::class);
        $this->twigExtension->manyToOneOrderByFilter([1,2], [1]);
    }

    public function testWrongOrderBy()
    {
        $this->expectException(BadMethodCallException::class);
        $this->twigExtension->manyToOneOrderByFilter([1], [1]);
    }

    public function testWithOneParameter()
    {
        $this->assertEquals(
            '->orderBy(\'e.name\', \'DESC\');',
            $this->twigExtension->manyToOneOrderByFilter(['name'], ['DESC']),
            'manyToOneOrderByFilter returned the wrong expression with one parameter'
        );
    }

    public function testWithTwoParameter()
    {
        $this->assertEquals(
            '->orderBy(\'e.name\', \'DESC\')' . PHP_EOL . '->addOrderBy(\'e.title\', \'ASC\');',
            $this->twigExtension->manyToOneOrderByFilter(['name' ,'title'], ['DESC', 'ASC']),
            'manyToOneOrderByFilter returned the wrong expression with two parameters'
        );
    }
}