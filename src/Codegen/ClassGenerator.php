<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Codegen;

use Exception;
use GraphQL\Language\AST\DocumentNode;
use GraphQL\Language\AST\FieldNode;
use GraphQL\Language\AST\NodeKind;
use GraphQL\Language\AST\OperationDefinitionNode;
use GraphQL\Language\AST\SelectionSetNode;
use GraphQL\Language\AST\VariableDefinitionNode;
use GraphQL\Language\Printer;
use GraphQL\Language\Visitor;
use GraphQL\Type\Definition\CustomScalarType;
use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\IDType;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\OutputType;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Type\Definition\StringType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use GraphQL\Utils\TypeInfo;
use JsonSerializable;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Helpers;
use Nette\PhpGenerator\Parameter;
use Nette\PhpGenerator\PhpNamespace;
use Spawnia\Sailor\EndpointConfig;
use Spawnia\Sailor\ErrorFreeResult;
use Spawnia\Sailor\InputSerializer;
use Spawnia\Sailor\Mapper\DirectMapper;
use Spawnia\Sailor\Operation;
use Spawnia\Sailor\Result;
use Spawnia\Sailor\TypedObject;
use stdClass;

class ClassGenerator
{
    protected Schema         $schema;

    protected EndpointConfig $endpointConfig;

    protected string         $endpoint;

    protected OperationStack $operationStack;

    /**
     * @var array<string, ClassType>
     */
    protected array $inputClasses = [];

    /**
     * @var array<string, ClassType>
     */
    protected array $types = [];

    /**
     * @var array<int, ClassType>
     */
    protected array $classes = [];

    /**
     * @var array<int, string>
     */
    protected array $namespaceStack = [];

    public function __construct(Schema $schema, EndpointConfig $endpointConfig, string $endpoint)
    {
        $this->schema            = $schema;
        $this->endpointConfig    = $endpointConfig;
        $this->endpoint          = $endpoint;
        $this->namespaceStack [] = $endpointConfig->namespace();
    }

    /**
     * @return array<int, ClassType>
     */
    public function generate(DocumentNode $document): array
    {
        $this->defineTypeClasses();

        $typeInfo = new TypeInfo($this->schema);

        Visitor::visit(
            $document,
            Visitor::visitWithTypeInfo(
                $typeInfo,
                [
                    // A named operation, e.g. "mutation FooMutation", maps to a class
                    NodeKind::OPERATION_DEFINITION => [
                        'enter' => function (OperationDefinitionNode $operationDefinition): void {
                            $operationName = $operationDefinition->name->value;

                            // Generate a class to represent the query/mutation itself
                            $operation = new ClassType($operationName, $this->makeNamespace());

                            // The base class contains most of the logic
                            $this->ensureUse($operation, Operation::class);
                            $operation->setExtends(Operation::class);

                            // The execute method is the public API of the operation
                            $execute = $operation->addMethod('execute');
                            $execute->setStatic();

                            // It returns a typed result which is a new selection set class
                            $resultName = "{$operationName}Result";

                            // Related classes are put into a nested namespace
                            $this->namespaceStack [] = $operationName;
                            $resultClass             = $this->withCurrentNamespace($resultName);

                            $execute->setReturnType($resultClass);
                            $execute->setBody(<<<'PHP'
                            return self::executeOperation(...func_get_args());
                            PHP
                            );

                            // Store the actual query string in the operation
                            // TODO minify the query string
                            $document = $operation->addMethod('document');
                            $document->setStatic();
                            $document->setReturnType('string');
                            $operationString = Printer::doPrint($operationDefinition);
                            $document->setBody(<<<PHP
                            return /* @lang GraphQL */ '{$operationString}';
                            PHP
                            );

                            // Set the endpoint this operation belongs to
                            $document = $operation->addMethod('endpoint');
                            $document->setStatic();
                            $document->setReturnType('string');
                            $document->setBody(<<<PHP
                            return '{$this->endpoint}';
                            PHP
                            );

                            $result = new ClassType($resultName, $this->makeNamespace());
                            $this->ensureUse($result, Result::class);
                            $result->setExtends(Result::class);

                            $setData = $result->addMethod('setData');
                            $setData->setVisibility('protected');
                            $dataParam = $setData->addParameter('data');
                            $this->ensureUse($result, stdClass::class);
                            $dataParam->setType(stdClass::class);
                            $setData->setReturnType('void');
                            $setData->setBody(<<<PHP
                            \$this->data = {$operationName}::fromStdClass(\$data);
                            PHP
                            );

                            $dataProp = $result->addProperty('data');
                            $dataProp->setType(
                                $this->withCurrentNamespace($operationName)
                            );
                            $dataProp->setNullable(true);

                            $errorFreeResultName = "{$operationName}ErrorFreeResult";

                            $errorFree = $result->addMethod('errorFree');
                            $errorFree->setVisibility('public');
                            $errorFree->setReturnType(
                                $this->withCurrentNamespace($errorFreeResultName)
                            );
                            $errorFree->setBody(<<<PHP
                            return {$errorFreeResultName}::fromResult(\$this);
                            PHP
                            );

                            $errorFreeResult = new ClassType($errorFreeResultName, $this->makeNamespace());
                            $this->ensureUse($errorFreeResult, ErrorFreeResult::class);
                            $errorFreeResult->setExtends(ErrorFreeResult::class);

                            $errorFreeDataProp = $errorFreeResult->addProperty('data');
                            $errorFreeDataProp->setType(
                                $this->withCurrentNamespace($operationName)
                            );
                            $errorFreeDataProp->setNullable(false);

                            $this->operationStack                  = new OperationStack($operation);
                            $this->operationStack->result          = $result;
                            $this->operationStack->errorFreeResult = $errorFreeResult;
                            $this->operationStack->pushSelection(
                                $this->makeTypedObject($operationName)
                            );
                        },
                        'leave' => function (OperationDefinitionNode $_): void {
                            $execute = $this->operationStack->operation->getMethod('execute');

                            $parameters    = $execute->getParameters();
                            $addDocComment = false;
                            foreach ($parameters as $parameter) {
                                if ($parameter->getType() === 'array') {
                                    $addDocComment = true;
                                    break;
                                }
                            }

                            if (! $addDocComment) {
                                $execute->setComment(null);
                            }

                            // Store the current operation as we continue with the next one
                            foreach ($this->operationStack->classes() as $class) {
                                $this->classes [] = $class;
                            }
                        },
                    ],
                    NodeKind::VARIABLE_DEFINITION  => [
                        'enter' => function (VariableDefinitionNode $variableDefinition) use ($typeInfo): void {
                            $parameter = new Parameter($variableDefinition->variable->name->value);

                            if ($variableDefinition->defaultValue !== null) {
                                // TODO support default values
                            }

                            /** @var Type & InputType $type */
                            $type = $typeInfo->getInputType();

                            if ($type instanceof NonNull) {
                                $type = $type->getWrappedType();
                            } else {
                                $parameter->setNullable();
                                $parameter->setDefaultValue(null);
                            }

                            if ($type instanceof ListOfType) {
                                $parameter->setType('array');

                                $namedType = Type::getNamedType($type);
                                $parameter->setType('array');

                                if ($namedType instanceof ScalarType) {
                                    $typeReference = PhpType::forScalar($namedType);
                                } elseif ($namedType instanceof EnumType) {
                                    $typeReference = PhpType::forEnum($namedType);
                                } elseif ($namedType instanceof InputObjectType) {
                                    $typeReference = $this->makeInput($namedType);
                                    $this->ensureUse($this->operationStack->operation, $typeReference);
                                } else {
                                    throw new Exception('Unsupported type: ' . get_class($type));
                                }
                            } elseif ($type instanceof ScalarType) {
                                $typeReference = PhpType::forScalar($type);
                                $parameter->setType($typeReference);
                            } elseif ($type instanceof EnumType) {
                                $enumAdapter = $this->endpointConfig->enumAdapter();
                                $typeReference = $enumAdapter->typeHint($this->types[$type->name], $type);
                                $parameter->setType($typeReference);
                            } elseif ($type instanceof InputObjectType) {
                                // TODO create value objects to allow typing inputs strictly
                                $typeReference = $this->makeInput($type);
                                $this->ensureUse($this->operationStack->operation, $typeReference);
                                $parameter->setType($typeReference);
                            } else {
                                throw new Exception('Unsupported type: ' . get_class($type));
                            }

                            $typeParts = explode('\\', $typeReference);
                            $typeDoc = PhpType::phpDoc($type, array_pop($typeParts));
                            $execute = $this->operationStack->operation->getMethod('execute');
                            $comment = $execute->getComment();
                            $comment .= "@parameter {$typeDoc} \${$parameter->getName()}\n";
                            $execute->setComment($comment);

                            $this->operationStack->addParameterToOperation($parameter);
                        },
                    ],
                    NodeKind::FIELD                => [
                        'enter' => function (FieldNode $field) use ($typeInfo): void {
                            // We are only interested in the name that will come from the server
                            $fieldName = $field->alias !== null
                                ? $field->alias->value
                                : $field->name->value;

                            $selection = $this->operationStack->peekSelection();

                            /** @var Type & OutputType $type */
                            $type = $typeInfo->getType();
                            /** @var Type $namedType */
                            $namedType = Type::getNamedType($type);

                            if ($namedType instanceof ObjectType) {
                                $typedObjectName = ucfirst($fieldName);

                                // We go one level deeper into the selection set
                                // To avoid naming conflicts, we add on another namespace
                                $this->namespaceStack [] = $typedObjectName;
                                $typeReference           = "\\{$this->withCurrentNamespace($typedObjectName)}";

                                $this->operationStack->pushSelection(
                                    $this->makeTypedObject($typedObjectName)
                                );
                                $this->ensureUse($selection, TypedObject::class);
                                $this->ensureUse($selection, $typeReference);
                                $this->ensureUse($selection, stdClass::class);
                                $typeMapper = <<<PHP
                                static function (stdClass \$value): TypedObject {
                                    return {$typedObjectName}::fromStdClass(\$value);
                                }
                                PHP;
                            } elseif ($namedType instanceof ScalarType) {
                                $typeReference = PhpType::forScalar($namedType);
                                $this->ensureUse($selection, DirectMapper::class);
                                $typeMapper = <<<PHP
                                new DirectMapper()
                                PHP;
                            } elseif ($namedType instanceof EnumType) {
                                $typeReference = PhpType::forEnum($namedType);
                                $this->ensureUse($selection, DirectMapper::class);
                                // TODO consider mapping from enum instances
                                $typeMapper = <<<PHP
                                new DirectMapper()
                                PHP;
                            } else {
                                throw new Exception('Unsupported type ' . get_class($namedType) . ' found.');
                            }

                            $fieldProperty = $selection->addProperty($fieldName);
                            $typeParts     = explode('\\', $typeReference);
                            if ($type instanceof ListOfType || ($type instanceof NonNull && $type->getWrappedType() instanceof ListOfType)) {
                                $fieldProperty->setComment('@var ' . PhpType::phpDoc($type, array_pop($typeParts)));
                                $fieldProperty->setType('array');
                            } else {
                                $fieldProperty->setType($typeReference);
                            }

                            $fieldProperty->setNullable(! $type instanceof NonNull);

                            $fieldTypeMapper = $selection->addMethod(FieldTypeMapper::methodName($fieldName));
                            $fieldTypeMapper->setReturnType('callable');
                            $fieldTypeMapper->setBody(<<<PHP
                            return {$typeMapper};
                            PHP
                            );
                        },
                    ],
                    NodeKind::SELECTION_SET        => [
                        'leave' => function (SelectionSetNode $_): void {
                            // We are done with building this subtree of the selection set,
                            // so we move the top-most element to the storage
                            $this->operationStack->popSelection();

                            // The namespace moves up a level
                            array_pop($this->namespaceStack);
                        },
                    ],
                ]
            )
        );

        return $this->types + $this->classes;
    }

    protected function makeTypedObject(string $name): ClassType
    {
        $typedObject = new ClassType(
            $name,
            $this->makeNamespace()
        );
        $this->ensureUse($typedObject, TypedObject::class);
        $typedObject->addExtend(TypedObject::class);

        return $typedObject;
    }

    protected function makeNamespace(): PhpNamespace
    {
        return new PhpNamespace(
            $this->currentNamespace()
        );
    }

    protected function withCurrentNamespace(string $type): string
    {
        return "{$this->currentNamespace()}\\{$type}";
    }

    protected function currentNamespace(): string
    {
        return implode('\\', $this->namespaceStack);
    }

    protected function defineTypeClasses(): void
    {
        foreach ($this->schema->getTypeMap() as $type) {
            if ($type instanceof EnumType) {
                $this->types[$type->name] = $this->defineEnumTypeClass($type);
            }
        }
    }

    protected function defineEnumTypeClass(EnumType $type): ClassType
    {
        $enumClass = new ClassType(
            $type->name,
            new PhpNamespace($this->endpointConfig->namespace().'\\'.'Types')
        );
        $adapter = $this->endpointConfig->enumAdapter();

        return $adapter->define($enumClass, $type);
    }

    /**
     * @param ClassType $class
     * @param string    $name
     */
    protected function ensureUse(ClassType $class, string $name): void
    {
        if (! isset($class->getNamespace()->getUses()[Helpers::extractShortName($name)])) {
            $class->getNamespace()->addUse($name);
        }
    }

    protected function makeInput(InputObjectType $type): string
    {
        $inputName      = $type->name;
        $inputNamespace = $this->endpointConfig->namespace() . '\\Input';
        $inputReference = $inputNamespace . '\\' . $inputName;

        if (isset($this->inputClasses[$inputName])) {
            return $inputReference;
        }

        $inputObject = new ClassType($inputName, new PhpNamespace($inputNamespace));

        $this->ensureUse($inputObject, JsonSerializable::class);
        $this->ensureUse($inputObject, InputSerializer::class);
        $inputObject->addImplement(JsonSerializable::class);
        $inputObject->addTrait(InputSerializer::class);

        foreach ($type->getFields() as $field) {
            $property = $inputObject->addProperty($field->name);
            $property->setPrivate();

            $fieldType = $field->getType();

            // dd($fieldType);
            if ($fieldType instanceof NonNull) {
                $fieldType = $fieldType->getWrappedType();
            } else {
                $property->setNullable();
                $property->setValue(null);
            }

            if ($fieldType instanceof ListOfType) {
                $property->setType('array');
            } elseif ($fieldType instanceof ScalarType) {
                if ($fieldType instanceof CustomScalarType && class_exists('\\' . $fieldType->name)) {
                    $this->ensureUse($inputObject, '\\' . $fieldType->name);
                    $property->setType('\\' . $fieldType->name);
                } else {
                    $property->setType(PhpType::forScalar($fieldType));
                }
            } elseif ($fieldType instanceof EnumType) {
                $property->setType(PhpType::forEnum($fieldType));
            } elseif ($fieldType instanceof InputObjectType) {
                $typeReference = $this->makeInput($fieldType);
                // $this->ensureUse($this->operationStack->operation, $typeReference);
                $property->setType($typeReference);
            } else {
                throw new Exception('Unsupported type: ' . get_class($fieldType));
            }

            $inputObject->addMethod('get' . ucfirst($field->name))
                        ->setReturnType($typeReference ?? $property->getType())
                        ->setReturnNullable($property->isNullable())
                        ->setBody(<<<PHP
                            return \$this->{$field->name};
                        PHP
                        );

            $method = $inputObject->addMethod('set' . ucfirst($field->name))->setReturnType($inputReference);
            $method->addParameter($field->name)->setType($typeReference ?? $property->getType())->setNullable($property->isNullable());
            $method->setBody(<<<PHP
                \$this->{$field->name} = \${$field->name};

            return \$this;
            PHP
            );
        }

        $this->inputClasses[$inputName] = $inputObject;

        return $inputReference;
    }
}
