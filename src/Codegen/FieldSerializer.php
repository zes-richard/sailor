<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Codegen;

final class FieldSerializer
{
    const PREFIX = 'get';
    const SUFFIX = 'Serialized';

    public static function methodName(string $field): string
    {
        return self::PREFIX . ucfirst($field) . self::SUFFIX;
    }

    public static function fieldName(string $mapTypeMethod): string
    {
        return lcfirst(\Safe\substr(
            $mapTypeMethod,
            strlen(self::PREFIX),
            -strlen(self::SUFFIX)
        ));
    }
}
