<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Simple\Input;

use JsonSerializable;
use Spawnia\Sailor\InputSerializer;

class InputArg implements JsonSerializable
{
    use InputSerializer;

    private int $integer;

    private string $someID;

    private ?SplitArg $nested = null;

    public function getInteger(): int
    {
        return $this->integer;
    }

    public function setInteger(int $integer): InputArg
    {
        $this->integer = $integer;

        return $this;
    }

    public function getSomeID(): string
    {
        return $this->someID;
    }

    public function setSomeID(string $someID): InputArg
    {
        $this->someID = $someID;

        return $this;
    }

    public function getNested(): ?SplitArg
    {
        return $this->nested;
    }

    public function setNested(?SplitArg $nested): InputArg
    {
        $this->nested = $nested;

        return $this;
    }
}
