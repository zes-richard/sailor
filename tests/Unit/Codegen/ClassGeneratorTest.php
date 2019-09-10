<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Tests\Unit\Codegen;

use GraphQL\Language\Parser;
use GraphQL\Utils\BuildSchema;
use PHPUnit\Framework\TestCase;
use Spawnia\Sailor\Codegen\ClassGenerator;

class ClassGeneratorTest extends TestCase
{
    public function testGenerate(): void
    {
        $schema = BuildSchema::build('
        type Query {
            foo: ID
        }  
        ');
        $generator = new ClassGenerator($schema, 'Foo');

        $document = Parser::parse('
        query Foo {
            foo
        }
        ', [
            'noLocations' => true,
        ]);
        $operationClasses = $generator->generate($document);
        $this->assertCount(1, $operationClasses);
    }
}
