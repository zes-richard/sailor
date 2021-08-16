<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\InputArgs;

use Spawnia\Sailor\Result;
use stdClass;

class InputArgsResult extends Result
{
    public ?InputArgs $data;

    protected function setData(stdClass $data): void
    {
        $this->data = InputArgs::fromStdClass($data);
    }

    public function errorFree(): InputArgsErrorFreeResult
    {
        return InputArgsErrorFreeResult::fromResult($this);
    }
}
