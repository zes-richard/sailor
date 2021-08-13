<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\MyObjectQuery;

use Spawnia\Sailor\Result;
use stdClass;

class MyObjectQueryResult extends Result
{
    public ?MyObjectQuery $data;

    protected function setData(stdClass $data): void
    {
        $this->data = MyObjectQuery::fromStdClass($data);
    }

    public function errorFree(): MyObjectQueryErrorFreeResult
    {
        return MyObjectQueryErrorFreeResult::fromResult($this);
    }
}
