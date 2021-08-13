<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\MyObjectQuery\SingleObject;

use Spawnia\Sailor\Mapper\DirectMapper;
use Spawnia\Sailor\TypedObject;

class SingleObject extends TypedObject
{
    /** @var int|null */
    public $value;

    public function valueTypeMapper(): callable
    {
        return new DirectMapper();
    }
}
