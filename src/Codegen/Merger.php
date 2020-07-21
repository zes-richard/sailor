<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Codegen;

use GraphQL\Language\AST\DocumentNode;
use GraphQL\Language\AST\ExecutableDefinitionNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\NodeList;

class Merger
{
    /**
     * @param  array<string, DocumentNode>  $documents
     */
    public static function combine(array $documents): DocumentNode
    {
        $definitions = new NodeList([]);

        /** @var DocumentNode $document */
        foreach ($documents as $document) {
            /** @var ExecutableDefinitionNode&Node $definition */
            foreach ($document->definitions as $definition) {
                /** @var string $name We validated that operations are always named */
                $name = $definition->name->value;

                $definitions[$name] = $definition;
            }
        }

        return new DocumentNode([
            'definitions' => $definitions,
        ]);
    }
}
