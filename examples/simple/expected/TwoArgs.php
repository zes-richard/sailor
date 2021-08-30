<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple;

use Spawnia\Sailor\Operation;

class TwoArgs extends Operation
{
    public static function execute(?string $first = null, ?int $second = null): TwoArgs\TwoArgsResult
    {
        return self::executeOperation($first, $second);
    }

    public static function document(): string
    {
        return /* @lang GraphQL */ 'query TwoArgs($first: String, $second: Int) {
          twoArgs(first: $first, second: $second)
        }';
    }

    public static function endpoint(): string
    {
        return 'simple';
    }
}
