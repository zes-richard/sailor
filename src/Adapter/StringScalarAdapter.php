<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Adapter;

class StringScalarAdapter implements ScalarAdapter
{
    public function typeHint(): string
    {
        return 'string';
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function parse(string $value): string
    {
        return $value;
    }

    /**
     * @param mixed $value
     */
    public function serialize($value): string
    {
        return (string) $value;
    }
}
