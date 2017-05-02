<?php

namespace Ip\MeatUp\Mapping;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class ManyToOneOrderBy
{
    /**
     * @var string
     * @Required
     */
    public $propertyName;

    /**
     * @var string
     * @Required
     */
    public $orderDirection;
}