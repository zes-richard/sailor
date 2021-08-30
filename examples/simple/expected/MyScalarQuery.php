<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple;

use Spawnia\Sailor\Operation;

class MyScalarQuery extends Operation
{
    public static function execute(?string $arg = null): MyScalarQuery\MyScalarQueryResult
    {
        return self::executeOperation($arg);
    }

    public static function document(): string
    {
        return /* @lang GraphQL */ 'query MyScalarQuery($arg: String) {
          scalarWithArg(arg: $arg)
        }';
    }

    public static function endpoint(): string
    {
        return 'simple';
    }
}
