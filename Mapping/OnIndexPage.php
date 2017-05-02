<?php

namespace Ip\MeatUp\Mapping;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class OnIndexPage
{
    /**
     * @var int
     */
    public $position;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $filter;

    /**
     * @var array
     */
    public $filterParameters;
}