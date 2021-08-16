<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple;

use Spawnia\Sailor\Operation;
use Spawnia\Sailor\Simple\Input\InputArg;

class MyObjectArrayQuery extends Operation
{
    /**
     * @parameter array<int, int>|null $array1
     * @parameter array<int, string|null>|null $array2
     * @parameter array<int, int>|null $array3
     * @parameter array<int, InputArg|null>|null $array4
     */
    public static function execute(
        array $array1,
        array $array2,
        ?array $array3 = null,
        ?array $array4 = null
    ): MyObjectArrayQuery\MyObjectArrayQueryResult {
        return self::executeOperation(...func_get_args());
    }

    public static function document(): string
    {
        return /* @lang GraphQL */ 'query MyObjectArrayQuery($array1: [Int!]!, $array2: [SomeEnum]!, $array3: [Int!], $array4: [InputArg]) {
          singleObjectArrayArgs(array1: $array1, array2: $array2, array3: $array3, array4: $array4) {
            array1
            array2
            array3 {
              value
            }
            array4 {
              value
              ... on SomeObject {
                array1
              }
              __typename
            }
          }
        }';
    }

    public static function endpoint(): string
    {
        return 'simple';
    }
}
