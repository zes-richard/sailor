<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\MyObjectNestedQuery\SingleObject\Nested;

use Spawnia\Sailor\Mapper\DirectMapper;
use Spawnia\Sailor\TypedObject;

class Nested extends TypedObject
{
    /** @var int|null */
    public $value;

    public function valueTypeMapper(): callable
    {
        return new DirectMapper();
    }
}
