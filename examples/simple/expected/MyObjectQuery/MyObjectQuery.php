<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\MyObjectQuery;

use Spawnia\Sailor\Simple\MyObjectQuery\SingleObject\SingleObject;
use Spawnia\Sailor\TypedObject;
use stdClass;

class MyObjectQuery extends TypedObject
{
    /** @var SingleObject|null */
    public $singleObject;

    public function singleObjectTypeMapper(): callable
    {
        return static function (stdClass $value): TypedObject {
            return SingleObject::fromStdClass($value);
        };
    }
}
