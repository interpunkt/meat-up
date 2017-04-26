<?php

namespace DL\MeatUp\Mapping;

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
}