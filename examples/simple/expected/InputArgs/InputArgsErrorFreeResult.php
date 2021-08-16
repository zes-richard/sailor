<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\InputArgs;

use Spawnia\Sailor\ErrorFreeResult;

class InputArgsErrorFreeResult extends ErrorFreeResult
{
    public InputArgs $data;
}
