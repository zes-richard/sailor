<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\MyObjectArrayQuery\SingleObjectArrayArgs\Array4;

use Spawnia\Sailor\Mapper\DirectMapper;

class SomeObject extends Array4
{
    /** @var array<int, int> */
    protected array $array1;

    public function array1TypeMapper(): callable
    {
        return new DirectMapper();
    }

    /**
     * @return array<int, int>
     */
    public function getArray1(): array
    {
        return $this->array1;
    }
}
