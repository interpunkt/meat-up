<?php

namespace Ip\MeatUp\Mapping;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class ManyToOneChoice
{
    /**
     * @var array
     * @Required
     */
    public $labels;
}
