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
     * @var array
     * @Required
     */
    public $propertyNames;

    /**
     * @var array
     * @Required
     */
    public $orderDirections;
}
