<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple;

use Spawnia\Sailor\Operation;
use Spawnia\Sailor\Simple\Input\InputArg;

class InputArgs extends Operation
{
    public static function execute(InputArg $first, string $secondString, string $date): InputArgs\InputArgsResult
    {
        return self::executeOperation(...func_get_args());
    }

    public static function document(): string
    {
        return /* @lang GraphQL */ 'query InputArgs($first: InputArg!, $secondString: String!, $date: DateTime!) {
          inputArgs(first: $first, second: {string: $secondString, created: $date})
        }';
    }

    public static function endpoint(): string
    {
        return 'simple';
    }
}
