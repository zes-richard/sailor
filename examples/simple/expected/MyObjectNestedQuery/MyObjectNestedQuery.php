<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\MyObjectNestedQuery;

use Spawnia\Sailor\Simple\MyObjectNestedQuery\SingleObject\SingleObject;
use Spawnia\Sailor\TypedObject;
use stdClass;

class MyObjectNestedQuery extends TypedObject
{
    protected ?SingleObject $singleObject;

    public function singleObjectTypeMapper(): callable
    {
        return static function (stdClass $value): TypedObject {
            return SingleObject::fromStdClass($value);
        };
    }

    public function getSingleObject(): ?SingleObject
    {
        return $this->singleObject;
    }
}
