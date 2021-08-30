<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Adapter;

use GraphQL\Language\AST\EnumTypeDefinitionNode;
use Nette\PhpGenerator\ClassType;

class LaravelEnumAdapter implements EnumAdapter
{
    public function define(ClassType $enumClass, EnumTypeDefinitionNode $enumTypeDefinitionNode): ClassType
    {
        $enumClass->addImplement('BenSampo\Enum\Enum');

        $magicInstantiationMethods = '';
        foreach ($enumTypeDefinitionNode->values as $enumValue) {
            $name = $enumValue->name->value;
            $constant = $enumClass->addConstant($name, $name);

            $description = $enumValue->description;
            if (null !== $description) {
                $constant->addComment($description->value);
            }

            $magicInstantiationMethods .= "@method static static {$name}()";
        }

        $enumClass->addComment($magicInstantiationMethods);

        return $enumClass;
    }

    public function typeHint(ClassType $enumClass, EnumTypeDefinitionNode $enumTypeDefinitionNode): string
    {
        return $enumClass->getNamespace()->getName().'\\'.$enumClass->getName();
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
