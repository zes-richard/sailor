<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\MyObjectArrayQuery\SingleObjectArrayArgs;

use Spawnia\Sailor\Mapper\DirectMapper;
use Spawnia\Sailor\Simple\MyObjectArrayQuery\SingleObjectArrayArgs\Array3\Array3;
use Spawnia\Sailor\Simple\MyObjectArrayQuery\SingleObjectArrayArgs\Array4\Array4;
use Spawnia\Sailor\Simple\MyObjectArrayQuery\SingleObjectArrayArgs\Array4\SomeObject;
use Spawnia\Sailor\TypedObject;
use stdClass;

class SingleObjectArrayArgs extends TypedObject
{
    /** @var array<int, int> */
    protected array $array1;

    /** @var array<int, string|null> */
    protected array $array2;

    /** @var array<int, Array3>|null */
    protected ?array $array3;

    /** @var array<int, Array4|null>|null */
    protected ?array $array4;

    public function array1TypeMapper(): callable
    {
        return new DirectMapper();
    }

    /**
     * @return array<int, int>
     */
    public function getArray1(): array
    {
        return $this->array1;
    }

    public function array2TypeMapper(): callable
    {
        return new DirectMapper();
    }

    /**
     * @return array<int, string|null>
     */
    public function getArray2(): array
    {
        return $this->array2;
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

    public function array4TypeMapper(): callable
    {
        return static function (stdClass $value): TypedObject {
            switch ($value->__typename) {
                case 'SomeObject':
                    return SomeObject::fromStdClass($value);
                default:
                    return Array4::fromStdClass($value);
            }
        };
    }

    /**
     * @return array<int, Array4|null>|null
     */
    public function getArray4(): ?array
    {
        return $this->array4;
    }
}
