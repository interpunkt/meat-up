<?php

namespace Tests\Generator;

use DL\MeatUpBundle\Generator\FormGenerator;
use DL\MeatUpBundle\Util\ReflectionUtil;
use PHPUnit\Framework\TestCase;

class FormGeneratorTest extends TestCase
{
    private $reflection;
    private $meatUpDir;
    private $entityBundleNameSpace;

    protected function setUp()
    {
        $this->reflection = new ReflectionUtil('Tests\Entity\stringTest');
        $this->meatUpDir = __DIR__ . '..' . DIRECTORY_SEPARATOR . '../';
        $this->entityBundleNameSpace = 'Tests\Entity';
    }

    public function testGenerate()
    {
        print FormGenerator::generate(
            $this->reflection,
            $this->meatUpDir,
            $this->entityBundleNameSpace
        );
    }
}