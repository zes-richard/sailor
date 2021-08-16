<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\MyObjectArrayQuery;

use Spawnia\Sailor\Result;
use stdClass;

class MyObjectArrayQueryResult extends Result
{
    public ?MyObjectArrayQuery $data;

    protected function setData(stdClass $data): void
    {
        $this->data = MyObjectArrayQuery::fromStdClass($data);
    }

    public function errorFree(): MyObjectArrayQueryErrorFreeResult
    {
        return MyObjectArrayQueryErrorFreeResult::fromResult($this);
    }
}
