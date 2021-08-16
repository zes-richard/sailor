<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\MyObjectNestedQuery\SingleObject;

use Spawnia\Sailor\Simple\MyObjectNestedQuery\SingleObject\Nested\Nested;
use Spawnia\Sailor\TypedObject;
use stdClass;

class SingleObject extends TypedObject
{
    public ?Nested $nested;

    public function nestedTypeMapper(): callable
    {
        return static function (stdClass $value): TypedObject {
            return Nested::fromStdClass($value);
        };
    }
}
