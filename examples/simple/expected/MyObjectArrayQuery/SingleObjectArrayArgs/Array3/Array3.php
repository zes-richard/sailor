<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\MyObjectArrayQuery\SingleObjectArrayArgs\Array3;

use Spawnia\Sailor\Mapper\DirectMapper;
use Spawnia\Sailor\TypedObject;

class Array3 extends TypedObject
{
    public ?int $value;

    public function valueTypeMapper(): callable
    {
        return new DirectMapper();
    }
}
