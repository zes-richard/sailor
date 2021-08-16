<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\InputArgs;

use Spawnia\Sailor\Mapper\DirectMapper;
use Spawnia\Sailor\TypedObject;

class InputArgs extends TypedObject
{
    protected ?string $inputArgs;

    public function inputArgsTypeMapper(): callable
    {
        return new DirectMapper();
    }

    public function getInputArgs(): ?string
    {
        return $this->inputArgs;
    }
}
