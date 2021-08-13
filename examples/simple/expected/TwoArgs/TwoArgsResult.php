<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\TwoArgs;

use Spawnia\Sailor\Result;
use stdClass;

class TwoArgsResult extends Result
{
    public ?TwoArgs $data;

    protected function setData(stdClass $data): void
    {
        $this->data = TwoArgs::fromStdClass($data);
    }

    public function errorFree(): TwoArgsErrorFreeResult
    {
        return TwoArgsErrorFreeResult::fromResult($this);
    }
}
