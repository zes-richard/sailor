<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\TwoArgs;

use Spawnia\Sailor\Mapper\DirectMapper;
use Spawnia\Sailor\TypedObject;

class TwoArgs extends TypedObject
{
    protected ?string $twoArgs;

    public function twoArgsTypeMapper(): callable
    {
        return new DirectMapper();
    }

    public function getTwoArgs(): ?string
    {
        return $this->twoArgs;
    }
}
