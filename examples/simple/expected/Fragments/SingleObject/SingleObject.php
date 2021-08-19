<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\Fragments\SingleObject;

use Spawnia\Sailor\Mapper\DirectMapper;
use Spawnia\Sailor\Simple\Fragments\SingleObject\Array3\Array3;
use Spawnia\Sailor\TypedObject;
use stdClass;

class SingleObject extends TypedObject
{
    protected ?int $value;

    /** @var array<int, Array3>|null */
    protected ?array $array3;

    public function valueTypeMapper(): callable
    {
        return new DirectMapper();
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function array3TypeMapper(): callable
    {
        return static function (stdClass $value): TypedObject {
            return Array3::fromStdClass($value);
        };
    }

    /**
     * @return array<int, Array3>|null
     */
    public function getArray3(): ?array
    {
        return $this->array3;
    }
}
