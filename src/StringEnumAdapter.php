<?php

declare(strict_types=1);

namespace Spawnia\Sailor;

use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQL\Language\AST\EnumValueDefinitionNode;
use Nette\PhpGenerator\ClassType;

class StringEnumAdapter implements EnumAdapter
{
    public function define(ClassType $enumClass, EnumTypeDefinitionNode $enumTypeDefinitionNode): array
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

        return [
            'string',
            $enumClass,
        ];
    }

    public function parse(string $value, string $enumClass): string
    {
        return $value;
    }

    public function serialize($value, string $enumClass): string
    {
        if (! defined($enumClass.'::'.$value)) {
            throw new \InvalidArgumentException('TODO');
        }

        return $value;
    }
}
