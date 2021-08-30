<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Adapter;

use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQL\Language\AST\EnumValueDefinitionNode;
use Nette\PhpGenerator\ClassType;

class StringEnumAdapter implements EnumAdapter
{
    public function define(ClassType $enumClass, EnumTypeDefinitionNode $enumTypeDefinitionNode): ClassType
    {
        $description = $enumTypeDefinitionNode->description;
        if (null !== $description) {
            $enumClass->addComment($description->value);
        }

        /** @var EnumValueDefinitionNode $enumValue */
        foreach ($enumTypeDefinitionNode->values as $enumValue) {
            $name = $enumValue->name->value;
            $constant = $enumClass->addConstant($name, $name);

            $description = $enumValue->description;
            if (null !== $description) {
                $constant->addComment($description->value);
            }
        }

        return $enumClass;
    }

    public function typeHint(ClassType $enumClass, EnumTypeDefinitionNode $enumTypeDefinitionNode): string
    {
        return 'string';
    }

    public function parse(string $value, string $enumClass): string
    {
        return $value;
    }

    public function serialize($value, string $enumClass): string
    {
        if (! defined($enumClass.'::'.$value)) {
            throw new \InvalidArgumentException("Could not serialize {$value} as an enum of class {$enumClass}.");
        }

        return $value;
    }
}
