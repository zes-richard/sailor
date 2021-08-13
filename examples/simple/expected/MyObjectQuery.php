<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple;

use Spawnia\Sailor\Operation;

class MyObjectQuery extends Operation
{
    public static function execute(): MyObjectQuery\MyObjectQueryResult
    {
        return self::executeOperation(...func_get_args());
    }

    public static function document(): string
    {
        return /* @lang GraphQL */ 'query MyObjectQuery {
          singleObject {
            value
          }
        }';
    }

    public static function endpoint(): string
    {
        return 'simple';
    }
}
