<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\MyObjectNestedQuery;

use Spawnia\Sailor\Result;
use stdClass;

class MyObjectNestedQueryResult extends Result
{
    public ?MyObjectNestedQuery $data;

    protected function setData(stdClass $data): void
    {
        $this->data = MyObjectNestedQuery::fromStdClass($data);
    }

    public function errorFree(): MyObjectNestedQueryErrorFreeResult
    {
        return MyObjectNestedQueryErrorFreeResult::fromResult($this);
    }
}
