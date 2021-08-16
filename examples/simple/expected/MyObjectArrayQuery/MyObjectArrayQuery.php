<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\MyObjectArrayQuery;

use Spawnia\Sailor\Simple\MyObjectArrayQuery\SingleObjectArrayArgs\SingleObjectArrayArgs;
use Spawnia\Sailor\TypedObject;
use stdClass;

class MyObjectArrayQuery extends TypedObject
{
    public ?SingleObjectArrayArgs $singleObjectArrayArgs;

    public function singleObjectArrayArgsTypeMapper(): callable
    {
        return static function (stdClass $value): TypedObject {
            return SingleObjectArrayArgs::fromStdClass($value);
        };
    }
}
