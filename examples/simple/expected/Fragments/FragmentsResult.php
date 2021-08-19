<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\Fragments;

use Spawnia\Sailor\Result;
use stdClass;

class FragmentsResult extends Result
{
    public ?Fragments $data;

    protected function setData(stdClass $data): void
    {
        $this->data = Fragments::fromStdClass($data);
    }

    public function errorFree(): FragmentsErrorFreeResult
    {
        return FragmentsErrorFreeResult::fromResult($this);
    }
}
