<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Codegen;

use GraphQL\Type\Definition\CustomScalarType;
use GraphQL\Type\Definition\Type;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Helpers;
use Nette\PhpGenerator\Parameter;
use Spawnia\Sailor\Configuration;

class OperationStack
{
    public ClassType $operation;

    public ClassType $result;

    public ClassType $errorFreeResult;

    /** @var array<int, ClassType> */
    public array $selectionStack = [];

    /** @var array<int, ClassType> */
    public array $selectionStorage = [];

    public function __construct(ClassType $operation)
    {
        $this->operation = $operation;
    }

    public function pushSelection(ClassType $selectionClass): void
    {
        $this->selectionStack [] = $selectionClass;
    }

    /**
     * When building the current selection is finished, we move it to storage.
     */
    public function popSelection(): void
    {
        $selection = array_pop($this->selectionStack);
        if ($selection === null) {
            throw new \Exception('Emptied out the selection stack too quickly.');
        }

        $this->selectionStorage [] = $selection;
    }

    public function peekSelection(): ClassType
    {
        $selection = end($this->selectionStack);
        if ($selection === false) {
            throw new \Exception('The selection stack was unexpectedly empty.');
        }

        return $selection;
    }

    public function addParameterToOperation(Parameter $parameter, Type $type, string $typeReference): void
    {
        $typeParts = explode('\\', $typeReference);
        $typeDoc   = PhpType::phpDoc($type, array_pop($typeParts));

        $execute       = $this->operation->getMethod('execute');
        $parameterName = $parameter->getName();

        $comment = $execute->getComment();
        $comment .= "@parameter {$typeDoc} \${$parameterName}\n";
        $execute->setComment($comment);

        $parameters   = $execute->getParameters();
        $parameters[] = $parameter;

        if ($type instanceof CustomScalarType) {
            $phpNamespace = $this->operation->getNamespace();
            if (! isset($phpNamespace->getUses()[Helpers::extractShortName(Configuration::class)])) {
                $phpNamespace->addUse(Configuration::class);
            }

            $typeMapper    = "\${$parameterName}Serialized = Configuration::endpoint(self::endpoint())->scalarAdapter('{$type->name}')->serialize(\${$parameterName});\n\n";
            $parameterName .= 'Serialized';

            $execute->setBody($typeMapper . $execute->getBody());
        }

        $execute->setBody(substr($execute->getBody(), 0, -2) . (count($parameters) > 1 ? ', ' : '') . "\${$parameterName});");

        $execute->setParameters($parameters);
    }

    public function classes(): array
    {
        return array_merge([
            $this->operation,
            $this->result,
            $this->errorFreeResult,
        ], $this->selectionStorage);
    }
}
