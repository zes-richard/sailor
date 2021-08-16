<?php

namespace Spawnia\Sailor;

trait InputSerializer
{
    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), static function ($value) {
            return is_null($value);
        });
    }
}
