<?php

declare(strict_types=1);

namespace Spawnia\Sailor;

use GraphQL\Language\AST\EnumTypeDefinitionNode;
use Nette\PhpGenerator\ClassType;

interface EnumAdapter
{
    /**
     * Enhance the given class prototype with the given the enum AST.
     */
    public function define(ClassType $enumClass, EnumTypeDefinitionNode $enumTypeDefinitionNode): ClassType;

    /**
     * Parse the raw enum value from the API.
     *
     * @param class-string $enumClass Fully qualified class name of the generated enum class
     */
    public function parse(string $value, string $enumClass);

    /**
     * Serialize an internal enum value.
     *
     * @param class-string $enumClass Fully qualified class name of the generated enum class
     */
    public function serialize($value, string $enumClass): string;

    /**
     * The type hint to use in arguments of Operation::execute() and generated result classes.
     */
    public function typeHint(ClassType $enumClass, EnumTypeDefinitionNode $enumTypeDefinitionNode): string;
}
