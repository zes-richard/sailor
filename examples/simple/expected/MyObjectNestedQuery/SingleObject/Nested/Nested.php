<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\MyObjectNestedQuery\SingleObject\Nested;

use Spawnia\Sailor\Mapper\DirectMapper;
use Spawnia\Sailor\TypedObject;

class Nested extends TypedObject
{
    public ?int $value;

    public function valueTypeMapper(): callable
    {
        return new DirectMapper();
    }
}
