<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\Fragments\SingleObject\Array3;

use Spawnia\Sailor\Configuration;
use Spawnia\Sailor\Mapper\DirectMapper;
use Spawnia\Sailor\TypedObject;

class Array3 extends TypedObject
{
    protected ?int $value;
    protected ?string $enum;

    public function valueTypeMapper(): callable
    {
        return new DirectMapper();
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function enumTypeMapper(): callable
    {
        return static function ($value) {
            return Configuration::endpoint('simple')->enumAdapter()->parse($value, 'Spawnia\Sailor\Simple\Types\SomeEnum');
        };
    }

    public function getEnum(): ?string
    {
        return $this->enum;
    }
}
