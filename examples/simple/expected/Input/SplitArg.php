<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\Input;

use DateTime;
use JsonSerializable;
use Spawnia\Sailor\Configuration;
use Spawnia\Sailor\InputSerializer;

class SplitArg implements JsonSerializable
{
    use InputSerializer;

    private string $string;

    private DateTime $created;

    public function getString(): string
    {
        return $this->string;
    }

    public function setString(string $string): SplitArg
    {
        $this->string = $string;

        return $this;
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }

    public function setCreated(DateTime $created): SplitArg
    {
        $this->created = $created;

        return $this;
    }

    public function getCreatedSerialized(): string
    {
        return Configuration::endpoint('simple')->scalarAdapter('DateTime')->serialize($this->created);
    }
}
