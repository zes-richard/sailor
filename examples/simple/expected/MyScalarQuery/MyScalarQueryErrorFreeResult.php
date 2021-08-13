<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\MyScalarQuery;

use Spawnia\Sailor\ErrorFreeResult;

class MyScalarQueryErrorFreeResult extends ErrorFreeResult
{
    public MyScalarQuery $data;
}
