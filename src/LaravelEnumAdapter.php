<?php

namespace Spawnia\Sailor;

use GraphQL\Language\AST\EnumTypeDefinitionNode;
use Nette\PhpGenerator\ClassType;

class LaravelEnumAdapter implements EnumAdapter
{
    public function define(ClassType $enumClass, EnumTypeDefinitionNode $enumTypeDefinitionNode): array
    {
        $enumClass->addImplement('Bensampo\Enum');

        $magicInstantiationMethods = '';
        foreach ($enumTypeDefinitionNode->values as $enumValue) {
            $enumClass->addConstant(...);
            $magicInstantiationMethods .= '@method static static ' . $enumValue->name->value . '()';
        }

        return [
            $enumClass->getName(),
            $enumClass,
        ];
    }

    /**
     * @param class-string<BenSampo\Enum> $enumClass
     */
    public function parse(string $value, string $enumClass): Enum
    {
        return new $enumClass($value);
    }

    /**
     * @param Bensampo\Enum $value
     */
    public function serialize($value, string $enumClass): string
    {
        return $value->value;
    }
}
