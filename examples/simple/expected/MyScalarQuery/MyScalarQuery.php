<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\MyScalarQuery;

use Spawnia\Sailor\Mapper\DirectMapper;
use Spawnia\Sailor\TypedObject;

class MyScalarQuery extends TypedObject
{
    /** @var string|null */
    public $scalarWithArg;

    public function scalarWithArgTypeMapper(): callable
    {
        return new DirectMapper();
    }
}
