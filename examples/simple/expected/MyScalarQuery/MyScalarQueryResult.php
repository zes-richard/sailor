<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\MyScalarQuery;

use Spawnia\Sailor\Result;
use stdClass;

class MyScalarQueryResult extends Result
{
    public ?MyScalarQuery $data;

    protected function setData(stdClass $data): void
    {
        $this->data = MyScalarQuery::fromStdClass($data);
    }

    public function errorFree(): MyScalarQueryErrorFreeResult
    {
        return MyScalarQueryErrorFreeResult::fromResult($this);
    }
}
