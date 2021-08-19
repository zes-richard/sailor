<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple;

use Spawnia\Sailor\Operation;

class Fragments extends Operation
{
    public static function execute(): Fragments\FragmentsResult
    {
        return self::executeOperation(...func_get_args());
    }

    public static function document(): string
    {
        return /* @lang GraphQL */ 'query Fragments {
          singleObject {
            ...Test
          }
        }

        fragment Test on SomeObject {
          value
          array3 {
            value
            ...Test2
          }
        }

        fragment Test2 on SomeObject {
          enum
        }';
    }

    public static function endpoint(): string
    {
        return 'simple';
    }
}
