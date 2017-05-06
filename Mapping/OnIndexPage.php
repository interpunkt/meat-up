<?php

namespace Ip\MeatUp\Mapping;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class OnIndexPage
{
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