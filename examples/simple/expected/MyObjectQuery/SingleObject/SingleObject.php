<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\MyObjectQuery\SingleObject;

use DateTime;
use Spawnia\Sailor\Configuration;
use Spawnia\Sailor\Mapper\DirectMapper;
use Spawnia\Sailor\TypedObject;

class SingleObject extends TypedObject
{
    protected ?int $value;

    protected ?DateTime $date;

    public function valueTypeMapper(): callable
    {
        return new DirectMapper();
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function dateTypeMapper(): callable
    {
        return static function ($value) {
            return Configuration::endpoint('simple')->scalarAdapter('DateTime')->parse($value);
        };
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }
}
