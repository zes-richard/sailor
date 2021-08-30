<?php

declare(strict_types=1);

namespace Spawnia\Sailor;

use Spawnia\Sailor\Adapter\EnumAdapter;
use Spawnia\Sailor\Adapter\ScalarAdapter;
use Spawnia\Sailor\Adapter\StringEnumAdapter;
use Spawnia\Sailor\Adapter\StringScalarAdapter;

abstract class EndpointConfig
{
    /**
     * Instantiate a client that will resolve the GraphQL operations.
     */
    abstract public function makeClient(): Client;

    /**
     * The namespace the generated classes will be created in.
     */
    abstract public function namespace(): string;

    /**
     * Path to the directory where the generated classes will be put.
     */
    abstract public function targetPath(): string;

    /**
     * Where to look for .graphql files containing operations.
     */
    abstract public function searchPath(): string;

    /**
     * The location of the schema file that describes the endpoint.
     */
    abstract public function schemaPath(): string;

    public function enumAdapter(): EnumAdapter
    {
        return new StringEnumAdapter();
    }

    public function scalarAdapter(string $type): ScalarAdapter
    {
        return new StringScalarAdapter();
    }
}
