<?php

namespace Ip\MeatUp\Tests\Generator;

use Ip\MeatUp\Generator\FormGenerator;
use Ip\MeatUp\Twig\MeatUpTwig;
use Ip\MeatUp\Util\AnnotationUtil;
use Ip\MeatUp\Util\FormImportUtil;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use Twig_Environment;

class FormGeneratorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $annotationUtilMock;
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $formImportUtilMock;
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $meatUpTwigMock;
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $twigEnvironmentMock;

    public function setUp()
    {
        $this->annotationUtilMock = $this->createMock(AnnotationUtil::class);
        $this->formImportUtilMock = $this->createMock(FormImportUtil::class);
        $this->meatUpTwigMock = $this->createMock(MeatUpTwig::class);
        $this->twigEnvironmentMock = $this->createMock(Twig_Environment::class);
    }

    public function testVichUplodable()
    {
        $this->annotationUtilMock
            ->method('getProperties')
            ->willReturn(['vichUploadable', 'vichNameProperty']);

        $this->annotationUtilMock
            ->method('getName')
            ->will($this->onConsecutiveCalls('vichUploadable', 'vichNameProperty'));

        $this->annotationUtilMock
            ->method('getType')
            ->will($this->onConsecutiveCalls('vichUploadable', 'string'));

        $this->annotationUtilMock
            ->method('get')
            ->with(
                $this->equalTo('VichUploadable'),
                $this->equalTo('fileNameProperty'),
                $this->isType('string')
            )
            ->willReturn('vichNameProperty');

        $this->twigEnvironmentMock
            ->expects($this->once())
            ->method('render')
            ->with(
                $this->equalTo('formType.php.twig'),
                $this->equalTo(array(
                    'namespace' => '\Form\Type',
                    'className' => 'Type',
                    'fields' => array(
                        'vichUploadable' => array(
                            'type' => 'vichUploadable',
                            'name' => 'vichUploadable',
                            'required' => false,
                            'label' => 'VichUploadable',
                        ),
                    ),
                    'imports' => array(null),
                ))
            );

        $this->meatUpTwigMock
            ->method('get')
            ->willReturn($this->twigEnvironmentMock);

        $formGenerator = new FormGenerator(
            $this->meatUpTwigMock,
            $this->annotationUtilMock,
            $this->formImportUtilMock,
            ''
        );

        $formGenerator->generate();
    }
}
