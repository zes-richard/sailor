<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple;

use Spawnia\Sailor\Operation;

class MyObjectQuery extends Operation
{
    public static function execute(): MyObjectQuery\MyObjectQueryResult
    {
        return self::executeOperation();
    }

    public static function document(): string
    {
        return /* @lang GraphQL */ 'query MyObjectQuery {
          singleObject {
            value
            date
          }
        }';
    }

    public static function endpoint(): string
    {
        return 'simple';
    }
}
