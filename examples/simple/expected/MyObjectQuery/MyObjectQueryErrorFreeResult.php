<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\MyObjectQuery;

use Spawnia\Sailor\ErrorFreeResult;

class MyObjectQueryErrorFreeResult extends ErrorFreeResult
{
    public MyObjectQuery $data;
}
