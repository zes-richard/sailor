<?php

declare(strict_types=1);

namespace Spawnia\Sailor;

use GraphQL\Type\Introspection;
use GraphQL\Utils\BuildClientSchema;
use GraphQL\Utils\SchemaPrinter;
use Safe\Exceptions\FilesystemException;

class Introspector
{
    protected EndpointConfig $endpointConfig;

    public function __construct(EndpointConfig $endpointConfig)
    {
        $this->endpointConfig = $endpointConfig;
    }

    /**
     * @param array<string, mixed> $options
     *
     * @throws ResultErrorsException|FilesystemException
     */
    public function introspect(array $options = []): void
    {
        $client = $this->endpointConfig->makeClient();

        $optionsWithDefaults = array_merge([
            'directiveIsRepeatable' => true,
        ], $options);

        $introspectionResult = $client->request(
            Introspection::getIntrospectionQuery($optionsWithDefaults)
        );
        $introspectionResult->assertErrorFree();

        $schema = BuildClientSchema::build(
            Json::stdClassToAssoc($introspectionResult->data)
        );

        $schemaString = SchemaPrinter::doPrint($schema);

        \Safe\file_put_contents(
            $this->endpointConfig->schemaPath(),
            $schemaString
        );
    }
}
