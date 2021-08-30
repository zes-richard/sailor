<?php

namespace Spawnia\Sailor;

trait InputSerializer
{
    public function jsonSerialize()
    {
        $variables = get_object_vars($this);

        $jsonVariables = [];
        foreach ($variables as $name => $value) {
            if (is_null($value)) {
                continue;
            }

            $serializer = [$this, "get" . ucfirst($name) . 'Serialized'];
            if (is_callable($serializer)) {
                $value = $serializer($value);
            }

            $jsonVariables[$name] = $value;
        }

        return $jsonVariables;
    }
}
