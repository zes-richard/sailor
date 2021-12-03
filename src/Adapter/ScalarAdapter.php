<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Adapter;

interface ScalarAdapter
{
    /**
     * The type hint to use in arguments of Operation::execute() and generated result classes.
     */
    public function typeHint(): string;

    /**
     * Parse the raw scalar value from the API.
     *
     * @return mixed|null
     */
    public function parse(string $value);

    /**
     * Serialize an internal scalar value.
     *
     * @param mixed $value
     */
    public function serialize($value): ?string;
}
