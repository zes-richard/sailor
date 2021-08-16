<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\MyObjectArrayQuery\SingleObjectArrayArgs\Array4;

use Spawnia\Sailor\Mapper\DirectMapper;
use Spawnia\Sailor\TypedObject;

class Array4 extends TypedObject
{
    protected ?int $value;

    public function valueTypeMapper(): callable
    {
        return new DirectMapper();
    }

    public function getValue(): ?int
    {
        return $this->value;
    }
}
