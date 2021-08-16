<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\MyObjectArrayQuery\SingleObjectArrayArgs;

use Spawnia\Sailor\Mapper\DirectMapper;
use Spawnia\Sailor\Simple\MyObjectArrayQuery\SingleObjectArrayArgs\Array3\Array3;
use Spawnia\Sailor\TypedObject;
use stdClass;

class SingleObjectArrayArgs extends TypedObject
{
    /** @var array<int, int> */
    public array $array1;

    /** @var array<int, string|null> */
    public array $array2;

    /** @var array<int, Array3>|null */
    public ?array $array3;

    /** @var array<int, int|null>|null */
    public ?array $array4;

    public function array1TypeMapper(): callable
    {
        return new DirectMapper();
    }

    public function array2TypeMapper(): callable
    {
        return new DirectMapper();
    }

    public function array3TypeMapper(): callable
    {
        return static function (stdClass $value): TypedObject {
            return Array3::fromStdClass($value);
        };
    }

    public function array4TypeMapper(): callable
    {
        return new DirectMapper();
    }
}
