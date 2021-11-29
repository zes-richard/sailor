<?php

declare(strict_types=1);

use Spawnia\Sailor\Adapter\DateTimeScalarAdapter;
use Spawnia\Sailor\Adapter\ScalarAdapter;
use Spawnia\Sailor\Adapter\StringScalarAdapter;
use Spawnia\Sailor\Client;
use Spawnia\Sailor\EndpointConfig;
use Spawnia\Sailor\Response;
use Spawnia\Sailor\Testing\MockClient;

return [
    'simple' => new class extends EndpointConfig
    {
        public function namespace(): string
        {
            return 'Spawnia\Sailor\Simple';
        }

        public function targetPath(): string
        {
            return __DIR__.'/generated';
        }

        public function searchPath(): string
        {
            return __DIR__.'/src';
        }

        public function schemaPath(): string
        {
            return __DIR__.'/schema.graphqls';
        }

        public function makeClient(): Client
        {
            return new \Spawnia\Sailor\Client\Guzzle(
                'http://192.168.5.53:3001/graphql',
            );/*

            $mockClient = new MockClient();

            $mockClient->responseMocks [] = static function (): Response {
                return Response::fromStdClass((object) [
                    'data' => (object) [
                        'singleObject' => (object) [
                            'value' => 42,
                        ],
                    ],
                ]);
            };

            return $mockClient;*/
        }

        public function scalarAdapter(string $type): ScalarAdapter
        {
            if ($type === 'DateTime') {
                return new DateTimeScalarAdapter();
            }

            return new StringScalarAdapter();
        }
    },
];
